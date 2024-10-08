<?php

namespace DZunke\FeatureFlagsBundle\Tests;

use DZunke\FeatureFlagsBundle\Toggle;
use PHPUnit\Framework\TestCase;

class ToggleTest extends TestCase
{

    public function testDefaultStateIsTrue(): void
    {
        $sut = new Toggle();

        $this->assertTrue($sut->isActive('test'));
    }

    public function testDefaultState(): void
    {
        $sut = new Toggle();

        $this->assertInstanceOf(Toggle::class, $sut->setDefaultState(true));
        $this->assertInstanceOf(Toggle::class, $sut->setDefaultState(false));
    }

    public function testGetAndAddFlag(): void
    {
        $flagMock = self::createMock(Toggle\Flag::class);
        $flagMock->method('__toString')->willReturn('test_flag');

        $sut = new Toggle();

        $this->assertInstanceOf(Toggle::class, $sut->addFlag($flagMock));
        $this->assertNull($sut->getFlag('nothingShouldBeReturned'));
        $this->assertSame($flagMock, $sut->getFlag('test_flag'));
    }

    public function testIsActive(): void
    {
        $flagMock = $this->createMock(Toggle\Flag::class);
        $flagMock->method('__toString')->willReturn('MySpecialFeature');
        $flagMock->method('isActive')->will($this->onConsecutiveCalls(true, false));

        $sut = new Toggle();
        $sut->addFlag($flagMock);

        $this->assertTrue($sut->isActive('MySpecialFeature'));
        $this->assertFalse($sut->isActive('MySpecialFeature'));
    }
}
