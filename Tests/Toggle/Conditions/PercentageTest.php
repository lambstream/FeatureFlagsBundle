<?php

namespace DZunke\FeatureFlagsBundle\Tests\Toggle\Conditions;

use DZunke\FeatureFlagsBundle\Toggle\Conditions\AbstractCondition;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\Percentage;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PercentageTest extends TestCase
{
    /**
     * @var RequestStack
     */
    private $requestStackMock;

    public function setUp() : void
    {
        $requestMock = $this->createMock(Request::class);
        $this->requestStackMock = $this->createMock(RequestStack::class);
        $this->requestStackMock->method('getMainRequest')->willReturn($requestMock);

    }

    public function testItExtendsCorrectly(): void
    {
        $sut = new Percentage($this->requestStackMock);

        $this->assertInstanceOf(AbstractCondition::class, $sut);
        $this->assertInstanceOf(ConditionInterface::class, $sut);
    }

    public function testItThrowsExceptionWhenPercentageIsNotSet(): void
    {
        $this->expectException(Exception::class);

        $sut = new Percentage($this->requestStackMock);
        $sut->validate([], 'nothing');
    }

    public function testItReturnsTrueWhenCookieIsAlreadySet(): void
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->cookies = new InputBag([Percentage::BASIC_COOKIE_NAME => '1']);

        $requestStackMock = $this->createMock(RequestStack::class);
        $requestStackMock->method('getMainRequest')->willReturn($requestMock);

        $sut = new Percentage($requestStackMock);
        $this->assertTrue($sut->validate([
            'percentage' => 798,
        ]));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsBoolWhenCookieIsNotSet(): void
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->cookies = new InputBag();

        $requestStackMock = $this->createMock(RequestStack::class);
        $requestStackMock->method('getMainRequest')->willReturn($requestMock);

        $sut = new Percentage($requestStackMock);
        self::assertIsBool($sut->validate(['percentage' => 3]));
    }

    public function testToString(): void
    {
        $sut = new Percentage($this->requestStackMock);

        $this->assertSame('percentage', (string) $sut);
    }

}
