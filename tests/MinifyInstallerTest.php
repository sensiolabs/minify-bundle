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

namespace Sensiolabs\MinifyBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\MinifyBundle\Exception\InstallException;
use Sensiolabs\MinifyBundle\MinifyInstaller;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

#[CoversClass(MinifyInstaller::class)]
class MinifyInstallerTest extends TestCase
{
    public function testExceptionIsThrownWhenBinaryCannotBeDownloaded(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($this->createMockResponse(500));
        $installer = new MinifyInstaller('/tmp/minify/'.rand(5, 999), $httpClient);

        $this->expectException(InstallException::class);
        $installer->install();
    }

    private function createMockResponse(int $statusCode, string $content = ''): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn($statusCode);
        $response->method('getContent')->willReturn($content);

        return $response;
    }
}
