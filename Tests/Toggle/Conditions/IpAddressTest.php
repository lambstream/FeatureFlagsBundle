<?php

namespace DZunke\FeatureFlagsBundle\Tests\Toggle\Conditions;

use DZunke\FeatureFlagsBundle\Context;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\AbstractCondition;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\IpAddress;
use PHPUnit\Framework\TestCase;

class IpAddressTest extends TestCase
{

    public function testItExtendsCorrectly(): void
    {
        $sut = new IpAddress();

        $this->assertInstanceOf(AbstractCondition::class, $sut);
        $this->assertInstanceOf(ConditionInterface::class, $sut);
    }

    public function testItReturnsTrue(): void
    {
        $contextMock = $this->createMock(Context::class);
        $contextMock->method('get')->will($this->onConsecutiveCalls('127.0.0.1', '169.168.1.12'));

        $sut = new IpAddress();
        $sut->setContext($contextMock);

        $array = [
            '192.168.0.1',
            '172.16.12.3',
            '127.0.0.1',
            '169.168.1.123'
        ];

        $this->assertTrue($sut->validate($array));
        $this->assertFalse($sut->validate($array));
    }

    public function testToString(): void
    {
        $sut = new IpAddress();

        $this->assertSame('ip_address', (string) $sut);
    }

}
