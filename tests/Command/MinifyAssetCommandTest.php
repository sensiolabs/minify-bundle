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
use PHPUnit\Framework\TestCase;
use Sensiolabs\MinifyBundle\Command\MinifyAssetCommand;
use Sensiolabs\MinifyBundle\Minifier\MinifierInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

#[CoversClass(MinifyAssetCommand::class)]
class MinifyAssetCommandTest extends TestCase
{
    public function testMinifyAssetCommandOutputsMinifiedContentToConsole(): void
    {
        $minifier = $this->createMock(MinifierInterface::class);
        $minifier->method('minify')->willReturn('minified content');

        $command = new MinifyAssetCommand($minifier, __DIR__.'/../Fixtures');
        $tester = new CommandTester($command);

        $tester->execute(['input' => 'assets/css/style.css']);
        $this->assertSame(Command::SUCCESS, $tester->getStatusCode());
        $this->assertStringContainsString('minified content', $tester->getDisplay());
    }

    public function testMinifyAssetCommandWritesMinifiedContentToFile(): void
    {
        $minifier = $this->createMock(MinifierInterface::class);
        $minifier->method('minify')->willReturn('minified content');

        $projectDir = realpath(__DIR__.'/../Fixtures');
        $command = new MinifyAssetCommand($minifier, $projectDir);
        $tester = new CommandTester($command);

        $outputFile = 'var/output.css';
        $tester->execute(['input' => 'assets/css/style.css', 'output' => $outputFile]);
        $this->assertSame(Command::SUCCESS, $tester->getStatusCode());
        $this->assertFileExists($outputFile = $projectDir.'/'.$outputFile);
        $this->assertStringEqualsFile($outputFile, 'minified content');
        unlink($outputFile);
    }

    public function testMinifyAssetCommandFailsWhenInputFileDoesNotExist(): void
    {
        $minifier = $this->createMock(MinifierInterface::class);

        $command = new MinifyAssetCommand($minifier, __DIR__.'/../Fixtures');
        $tester = new CommandTester($command);

        $tester->execute(['input' => 'nonexistent.css']);
        $this->assertSame(Command::FAILURE, $tester->getStatusCode());
        $this->assertStringContainsString('Cannot read file', $tester->getDisplay());
    }

    public function testMinifyAssetCommandFailsWhenInputFileIsNotCssOrJS(): void
    {
        $minifier = $this->createMock(MinifierInterface::class);

        $command = new MinifyAssetCommand($minifier, __DIR__.'/../Fixtures');
        $tester = new CommandTester($command);

        $tester->execute(['input' => 'MinifyBundleTestKernel.php']);
        $this->assertSame(Command::FAILURE, $tester->getStatusCode());
        $display = $tester->getDisplay();
        $this->assertStringContainsString('The type of', $display);
        $this->assertStringContainsString('TestKernel.php" is "php", it must be "css" or "js".', $display);
    }

    public function testMinifyAssetCommandFailsWhenOutputFileIsNotWritable(): void
    {
        $minifier = $this->createMock(MinifierInterface::class);

        $command = new MinifyAssetCommand($minifier, __DIR__.'/../Fixtures');
        $tester = new CommandTester($command);

        $tester->execute(['input' => 'assets/css/style.css', 'output' => '/\\   .']);
        $this->assertSame(Command::FAILURE, $tester->getStatusCode());
        $this->assertStringContainsString('Cannot write to file', $tester->getDisplay());
    }
}
