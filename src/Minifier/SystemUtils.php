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

namespace Sensiolabs\MinifyBundle\Minifier;

/**
 * @author Simon André <smn.andre@gmail.com>
 */
final class SystemUtils
{
    private const OS_WINDOWS = 'windows';
    private const OS_LINUX = 'linux';
    private const OS_DARWIN = 'darwin';
    private const OS_FREEBSD = 'freebsd';

    private const ARCH_ARM64 = 'arm64';
    private const ARCH_AMD64 = 'amd64';
    private const ARCH_X86 = 'x86';

    public function __construct(
        private ?string $platform = null,
        private ?string $architecture = null,
    ) {
        $this->platform = $platform ? $this->getPlatform($platform) : null;
        $this->architecture = $architecture ? $this->getArchitecture($architecture) : null;
    }

    public static function create(): self
    {
        return new self(\PHP_OS_FAMILY, \php_uname('m'));
    }

    public function match(string $release): bool
    {
        if (null === $this->platform || null === $this->architecture) {
            return false;
        }

        return str_contains($release, $this->platform) && str_contains($release, $this->architecture);
    }

    private function getPlatform(string $platform): ?string
    {
        return match (\strtolower($platform)) {
            'freebsd' => self::OS_FREEBSD,
            'darwin' => self::OS_DARWIN,
            'linux' => self::OS_LINUX,
            'windows' => self::OS_WINDOWS,
            default => null,
        };
    }

    private function getArchitecture(string $architecture): ?string
    {
        return match (\strtolower($architecture)) {
            'amd64', 'x86_64' => self::ARCH_AMD64,
            'aarch64', 'arm64' => self::ARCH_ARM64,
            'i386', 'i686' => self::ARCH_X86,
            default => null,
        };
    }
}
