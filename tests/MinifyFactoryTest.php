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
use Sensiolabs\MinifyBundle\Exception\BinaryNotFoundException;
use Sensiolabs\MinifyBundle\Exception\LogicException;
use Sensiolabs\MinifyBundle\Minifier\MinifierInstallerInterface;
use Sensiolabs\MinifyBundle\MinifyFactory;

#[CoversClass(MinifyFactory::class)]
final class MinifyFactoryTest extends TestCase
{
    public function testExceptionIsThrownWhenBinaryNotFound(): void
    {
        $this->expectException(BinaryNotFoundException::class);
        $factory = new MinifyFactory(false, $this->createMock(MinifierInstallerInterface::class));
        $factory->create();
    }

    public function testExceptionIsThrownWhenInstallerIsNullAndBinaryPathIsFalse(): void
    {
        $this->expectException(LogicException::class);
        $factory = new MinifyFactory(false, null);
        $factory->create();
    }

    public function testInstallerIsCalledWhenBinaryNotFound(): void
    {
        $installer = $this->createMock(MinifierInstallerInterface::class);
        $installer->expects($this->once())->method('install');
        $installer->method('isInstalled')->willReturn(false);
        $installer->method('getInstallBinaryPath')->willReturn('/usr/local/bin/minify');

        $this->expectException(BinaryNotFoundException::class);
        $factory = new MinifyFactory(false, $installer);
        $factory->create();
    }
}
