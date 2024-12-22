<?php

declare(strict_types=1);

/*
 * This file is part of the SensioLabs MinifyBundle package.
 *
 * (c) Simon André - Sensiolabs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sensiolabs\MinifyBundle;

use Sensiolabs\MinifyBundle\Exception\InstallException;
use Sensiolabs\MinifyBundle\Exception\LogicException;
use Sensiolabs\MinifyBundle\Minifier\MinifierInstallerInterface;
use Sensiolabs\MinifyBundle\Minifier\SystemUtils;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @author Simon André <smn.andre@gmail.com>
 */
final class MinifyInstaller implements MinifierInstallerInterface
{
    private const RELEASES_API_URL = 'https://api.github.com/repos/tdewolff/minify/releases';
    private readonly HttpClientInterface $httpClient;
    private readonly Filesystem $filesystem;

    public function __construct(
        private readonly string $installDirectory,
        ?HttpClientInterface $httpClient = null,
    ) {
        if (null === $httpClient && !class_exists(HttpClient::class)) {
            throw new LogicException(\sprintf('The "%s" class needs an HTTP client to download the minify binary. Try running "composer require symfony/http-client".', self::class));
        }
        $this->httpClient = $httpClient ?? HttpClient::create();
        $this->filesystem = new Filesystem();
    }

    public function install(string $version = self::VERSION_LATEST, bool $force = false): void
    {
        if ($this->isInstalled() && !$force) {
            return;
        }

        $this->download($version);
    }

    public function isInstalled(): bool
    {
        return file_exists($this->getInstallBinaryPath()) && is_executable($this->getInstallBinaryPath());
    }

    public function getInstallBinaryPath(): string
    {
        return Path::join($this->installDirectory, 'minify');
    }

    public function download(string $version): void
    {
        $releaseAsset = $this->getReleaseAsset($version);
        $releaseDownloadUrl = $releaseAsset['browser_download_url'];

        $tempDir = sys_get_temp_dir().'/minify';
        $this->filesystem->mkdir($tempDir);

        $downloadFilename = Path::join($tempDir, basename($releaseDownloadUrl));
        $response = $this->httpClient->request('GET', $releaseDownloadUrl, [
            'headers' => [
                'Accept' => 'application/octet-stream',
            ],
        ]);
        if (200 !== $response->getStatusCode()) {
            throw new InstallException(sprintf('Error downloading the minify binary from GitHub "%s".', $response->getContent(false)));
        }
        foreach ($this->httpClient->stream($response) as $chunk) {
            $this->filesystem->appendToFile($downloadFilename, $chunk->getContent(), true);
        }

        $this->filesystem->mkdir(dirname($this->getInstallBinaryPath()));

        if (str_ends_with($downloadFilename, '.zip')) {
            // Windows archive (minify.exe)
            $archive = new \ZipArchive();
            if (true !== $archive->open($downloadFilename)) {
                throw new InstallException(sprintf('Error opening archive "%s".', $downloadFilename));
            }
            if (false === $archive->extractTo($tempDir, 'minify.exe')) {
                throw new InstallException(sprintf('Error extracting minify.exe from archive "%s".', $downloadFilename));
            }
            $archive->close();
            $this->filesystem->copy(Path::join($tempDir, 'minify.exe'), $this->getInstallBinaryPath());
        } else {
            $archive = new \PharData($downloadFilename);
            try {
                $archive->extractTo($tempDir, ['minify'], true);
            } catch (\Exception $e) {
                throw new InstallException(sprintf('Error extracting the binary from archive "%s".', $downloadFilename), 0, $e);
            }
            $this->filesystem->copy(Path::join($tempDir, 'minify'), $this->getInstallBinaryPath());
        }

        $this->filesystem->remove($tempDir);
    }

    /**
     * @return array{
     *  name: string,
     *  browser_download_url: string,
     *  content_type: string,
     * }
     */
    private function getReleaseAsset(string $version): array
    {
        $versionUrl = self::VERSION_LATEST === $version ? $version : 'tags/'.$version;
        $response = $this->httpClient->request('GET', self::RELEASES_API_URL.'/'.$versionUrl, [
            'headers' => ['Accept' => 'application/json'],
            'max_redirects' => 2,
        ]);
        if (200 !== $response->getStatusCode()) {
            throw new InstallException(sprintf('The release "%s" does not exist.', $version));
        }

        $systemUtils = SystemUtils::create();
        foreach ($response->toArray()['assets'] ?? [] as $asset) {
            if ($systemUtils->match($asset['name'])) {
                return $asset;
            }
        }

        throw new InstallException(sprintf('Unable to find a binary for release "%s".', $version));
    }
}
