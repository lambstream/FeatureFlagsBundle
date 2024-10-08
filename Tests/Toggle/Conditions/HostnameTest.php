<?php

namespace DZunke\FeatureFlagsBundle\Tests\Toggle\Conditions;

use DZunke\FeatureFlagsBundle\Context;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\AbstractCondition;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\Hostname;
use PHPUnit\Framework\TestCase;

class HostnameTest extends TestCase
{

    public function testItExtendsCorrectly(): void
    {
        $sut = new Hostname();

        $this->assertInstanceOf(AbstractCondition::class, $sut);
        $this->assertInstanceOf(ConditionInterface::class, $sut);
    }

    public function testItReturnsBoolean(): void
    {
        $contextMock = $this->createMock(Context::class);
        $contextMock->method('get')->will($this->onConsecutiveCalls('myhostname', 'thirdhostname'));

        $sut = new Hostname();
        $sut->setContext($contextMock);

        $array = [
            'firsthostname',
            'secondhostname',
            'thirdhostname',
        ];

        $this->assertFalse($sut->validate($array));
        $this->assertTrue($sut->validate($array));
    }

    public function testToString(): void
    {
        $sut = new Hostname();

        $this->assertSame('hostname', (string) $sut);
    }

}
