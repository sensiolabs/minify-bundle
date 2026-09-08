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

namespace Sensiolabs\MinifyBundle\Tests\Minifier;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sensiolabs\MinifyBundle\Minifier\SystemUtils;

#[CoversClass(SystemUtils::class)]
class SystemUtilsTest extends TestCase
{
    public function testCreateReturnsReleaseUtilsInstance(): void
    {
        $releaseUtils = SystemUtils::create();
        $this->assertInstanceOf(SystemUtils::class, $releaseUtils);
    }

    public function testMatchReturnsFalseWhenPlatformIsNull(): void
    {
        $releaseUtils = new SystemUtils(null, 'amd64');
        $this->assertFalse($releaseUtils->match('release'));
    }

    public function testMatchReturnsFalseWhenArchitectureIsNull(): void
    {
        $releaseUtils = new SystemUtils('linux', null);
        $this->assertFalse($releaseUtils->match('release'));
    }

    public function testMatchReturnsTrueForMatchingPlatformAndArchitecture(): void
    {
        $releaseUtils = new SystemUtils('linux', 'amd64');
        $this->assertTrue($releaseUtils->match('linux-amd64'));
    }

    public function testMatchReturnsFalseForNonMatchingPlatform(): void
    {
        $releaseUtils = new SystemUtils('linux', 'amd64');
        $this->assertFalse($releaseUtils->match('windows-amd64'));
    }

    public function testMatchReturnsFalseForNonMatchingArchitecture(): void
    {
        $releaseUtils = new SystemUtils('linux', 'amd64');
        $this->assertFalse($releaseUtils->match('linux-arm64'));
    }
}
