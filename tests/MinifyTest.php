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
use Sensiolabs\MinifyBundle\Exception\RuntimeException;
use Sensiolabs\MinifyBundle\Minifier\Options\HtmlOptions;
use Sensiolabs\MinifyBundle\Minify;
use Symfony\Component\Filesystem\Filesystem;

#[CoversClass(Minify::class)]
class MinifyTest extends TestCase
{
    private const FIXTURES_PATH = __DIR__.'/Fixtures';
    private const FIXTURES_BINARY_PATH = __DIR__.'/Fixtures/bin/fakify';

    protected function setUp(): void
    {
        (new Filesystem())->chmod(self::FIXTURES_BINARY_PATH, 0755);
    }

    public function testMinifyReturnsOutputOnSuccess(): void
    {
        $minify = new Minify(self::FIXTURES_BINARY_PATH);
        $input = file_get_contents(self::FIXTURES_PATH.'/assets/css/style.css');
        $this->assertIsString($input);

        $this->assertSame($input, $minify->minify($input, 'css'));
    }

    public function testMinifyThrowsRuntimeExceptionOnProcessException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Minify error 127: "Command not found".');

        $minify = new Minify('foo');

        $minify->minify('input content', 'css');
    }

    public function testMinifyThrowsRuntimeExceptionOnProcessFailure(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Minify error 1: "General error".');

        $minify = new Minify(self::FIXTURES_BINARY_PATH);

        $minify->minify('input content', 'js');
    }

    public function testMinifyForwardsOptionsCliArgs(): void
    {
        $minify = new Minify(self::FIXTURES_BINARY_PATH);
        $output = $minify->minify('<html></html>', 'html', new HtmlOptions(keepDocumentTags: true));

        $this->assertSame('<html></html>|args=--html-keep-document-tags', $output);
    }

    public function testMinifyPassesNoExtraArgsWhenOptionsHaveNoFlags(): void
    {
        $minify = new Minify(self::FIXTURES_BINARY_PATH);
        $output = $minify->minify('<html></html>', 'html', new HtmlOptions());

        $this->assertSame('<html></html>|args=', $output);
    }

    public function testMinifyThrowsWhenOptionsTypeDoesNotMatch(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Options type "html" does not match minify type "css".');

        $minify = new Minify(self::FIXTURES_BINARY_PATH);
        $minify->minify('input', 'css', new HtmlOptions());
    }
}
