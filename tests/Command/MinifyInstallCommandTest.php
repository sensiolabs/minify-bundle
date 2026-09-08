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

namespace Sensiolabs\MinifyBundle\Tests\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use Sensiolabs\MinifyBundle\Command\MinifyInstallCommand;
use Sensiolabs\MinifyBundle\Minifier\MinifierInstallerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

#[CoversClass(MinifyInstallCommand::class)]
class MinifyInstallCommandTest extends KernelTestCase
{
    public function testMinifyInstallCommandInstallsBinaryWhenNotInstalled(): void
    {
        $minifyInstaller = $this->createMock(MinifierInstallerInterface::class);
        $minifyInstaller->method('isInstalled')->willReturn(false);
        $minifyInstaller->expects($this->once())->method('install');

        $command = new MinifyInstallCommand($minifyInstaller);
        $tester = new CommandTester($command);

        $tester->execute([]);
        $this->assertSame(Command::SUCCESS, $tester->getStatusCode());
        $this->assertStringContainsString('The Minify binary has been installed', $tester->getDisplay());
    }

    public function testMinifyInstallCommandDoesNotReinstallBinaryWhenAlreadyInstalled(): void
    {
        $minifyInstaller = $this->createMock(MinifierInstallerInterface::class);
        $minifyInstaller->method('isInstalled')->willReturn(true);
        $minifyInstaller->expects($this->never())->method('install');

        $command = new MinifyInstallCommand($minifyInstaller);
        $tester = new CommandTester($command);

        $tester->execute([]);
        $this->assertSame(Command::SUCCESS, $tester->getStatusCode());
        $this->assertStringContainsString('The Minify binary is already installed', $tester->getDisplay());
    }

    public function testMinifyInstallCommandReinstallsBinaryWhenForceOptionIsUsed(): void
    {
        $minifyInstaller = $this->createMock(MinifierInstallerInterface::class);
        $minifyInstaller->method('isInstalled')->willReturn(true);
        $minifyInstaller->expects($this->once())->method('install');

        $command = new MinifyInstallCommand($minifyInstaller);
        $tester = new CommandTester($command);

        $tester->execute(['--force' => true]);
        $this->assertSame(Command::SUCCESS, $tester->getStatusCode());
        $this->assertStringContainsString('The binary will be re-installed', $tester->getDisplay());
        $this->assertStringContainsString('The Minify binary has been installed', $tester->getDisplay());
    }
}
