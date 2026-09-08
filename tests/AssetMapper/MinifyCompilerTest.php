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

namespace Sensiolabs\MinifyBundle\Tests\AssetMapper;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\MinifyBundle\AssetMapper\MinifierCompiler;
use Sensiolabs\MinifyBundle\Exception\RuntimeException;
use Sensiolabs\MinifyBundle\Minifier\MinifierInterface;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\AssetMapper\MappedAsset;

#[CoversClass(MinifierCompiler::class)]
class MinifyCompilerTest extends TestCase
{
    public function testSupportsReturnsFalseWhenExtensionNotSupported(): void
    {
        $minifier = $this->createMock(MinifierInterface::class);
        $minifierCompiler = new MinifierCompiler($minifier);

        $asset = new MappedAsset(
            'asset.css',
            '/source/asset.css',
            '/public/asset.png',
        );

        $this->assertFalse($minifierCompiler->supports($asset));
    }

    public function testSupportsReturnsFalseWhenAssetIsVendorAndIgnoreVendorIsTrue(): void
    {
        $minifier = $this->createMock(MinifierInterface::class);
        $minifierCompiler = new MinifierCompiler($minifier);

        $asset = new MappedAsset(
            'file.css',
            '/path/ignored/file.css',
            '/public/path.css',
            isVendor: true,
        );

        $this->assertFalse($minifierCompiler->supports($asset));
    }

    public function testSupportsReturnsFalseWhenAssetSourcePathMatchesIgnorePath(): void
    {
        $minifier = $this->createMock(MinifierInterface::class);
        $minifierCompiler = new MinifierCompiler($minifier, ['css', 'js'], ['*/ignored/*']);

        $asset = new MappedAsset(
            'file.css',
            '/path/ignored/file.css',
            '/public/path.css',
        );

        $this->assertFalse($minifierCompiler->supports($asset));
    }

    public function testSupportsReturnsTrueForValidAsset(): void
    {
        $minifier = $this->createMock(MinifierInterface::class);
        $minifierCompiler = new MinifierCompiler($minifier);

        $asset = new MappedAsset(
            'file.css',
            '/path/to/file.css',
            '/public/path.css',
        );
        $this->assertTrue($minifierCompiler->supports($asset));
    }

    public function testCompileReturnsMinifiedContentForCss(): void
    {
        $minifier = $this->createMock(MinifierInterface::class);
        $minifier->method('minify')->willReturn('minified content');

        $asset = new MappedAsset(
            'file.css',
            '/path/to/file.css',
            '/public/path.css',
        );
        $assetMapper = $this->createMock(AssetMapperInterface::class);

        $compiler = new MinifierCompiler($minifier);
        $this->assertSame('minified content', $compiler->compile('input content', $asset, $assetMapper));
    }

    public function testCompileReturnsMinifiedContentForJs(): void
    {
        $minifier = $this->createMock(MinifierInterface::class);
        $minifier->method('minify')->willReturn('minified content');

        $asset = new MappedAsset(
            'file.js',
            '/path/to/file.js',
            '/public/path.js',
        );
        $assetMapper = $this->createMock(AssetMapperInterface::class);

        $compiler = new MinifierCompiler($minifier);
        $this->assertSame('minified content', $compiler->compile('input content', $asset, $assetMapper));
    }

    public function testCompileThrowsRuntimeExceptionForInvalidType(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid type "txt".');

        $minifier = $this->createMock(MinifierInterface::class);

        $asset = new MappedAsset(
            'file.txt',
            '/path/to/file.txt',
            '/public/path.txt',
        );
        $assetMapper = $this->createMock(AssetMapperInterface::class);

        $compiler = new MinifierCompiler($minifier);
        $compiler->compile('input content', $asset, $assetMapper);
    }
}
