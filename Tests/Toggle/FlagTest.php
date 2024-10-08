<?php

namespace DZunke\FeatureFlagsBundle\Tests\Toggle;

use DZunke\FeatureFlagsBundle\Toggle\ConditionBag;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;
use DZunke\FeatureFlagsBundle\Toggle\Flag;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class FlagTest extends TestCase
{

    public function testIsActiveReturnsBool(): void
    {
        $conditionBagMock = $this->createMock(ConditionBag::class);

        //For the addComponent method
        $conditionBagMock->method('has')->willReturn(true);

        //For the isActive method
        $conditionMock = $this->createMock(ConditionInterface::class);
        $conditionMock->method('validate')->will($this->onConsecutiveCalls(true, false));

        $conditionBagMock->method('get')->willReturn($conditionMock);

        $sut = new Flag('MySpecialFeature', $conditionBagMock, false);
        $sut->addCondition('ipaddress', ['192.168.1.1']);

        $this->assertTrue($sut->isActive());
        $this->assertFalse($sut->isActive());
    }

    public function testIsActiveReturnsDefaultIfEmptyArray(): void
    {
        $conditionBagMock = $this->createMock(ConditionBag::class);

        $sut = new Flag('MySpecialFeature', $conditionBagMock, true);

        $this->assertTrue($sut->isActive('argument'));
    }

    public function testGetConfigReturnsArray(): void
    {
        $conditionBagMock = $this->createMock(ConditionBag::class);

        $sut = new Flag('MySpecialFeature', $conditionBagMock, false);

        self::assertIsArray($sut->getConfig());
    }

    public function testAddConditionThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $conditionBagMock = $this->createMock(ConditionBag::class);
        $conditionBagMock->method('has')
            ->willReturn(false);

        $sut = new Flag('MySpecialFeature', $conditionBagMock, true);
        $return = $sut->addCondition('throwexception', 'value');

        $this->assertInstanceOf(Flag::class, $return);
    }

    public function testToString(): void
    {
        $conditionBagMock = $this->createMock(ConditionBag::class);

        $sut = new Flag('MySpecialFeature', $conditionBagMock, true);

        $this->assertSame('MySpecialFeature', (string) $sut);
    }

}
