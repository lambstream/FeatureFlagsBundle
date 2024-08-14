<?php

namespace DZunke\FeatureFlagsBundle\Tests\Toggle\Conditions;

use DZunke\FeatureFlagsBundle\Context;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\AbstractCondition;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\Date;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DateTest extends TestCase
{
    /**
     * @var string
     */
    public const CURRENT_DATE = "2016-09-02T00:00:00Z";

    /**
     * @var string
     */
    public const FUTURE_DATE1 = "2020-12-25T12:00:00Z";

    /**
     * @var string
     */
    public const FUTURE_DATE2 = "2017-0-0T00:00:00Z";

    /**
     * @var string
     */
    public const PAST_DATE1 = "1970-01-01T00:00:00Z";

    /**
     * @var string
     */
    public const PAST_DATE2 = "2014-01-01T00:00:00Z";

    /**
     * Returns an instance of Date with a known "current" date.
     *
     * @return Date
     */
    public function getInstanceOfDate(): Date
    {
        $contextMock = $this->createMock(Context::class);

        $date = new Date();
        $date->setContext($contextMock);

        $reflection = new ReflectionClass($date);
        $reflection_property = $reflection->getProperty("currentDate");
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($date, self::CURRENT_DATE);

        return $date;
    }

    public function testItExtendsCorrectly(): void
    {
        $sut = $this->getInstanceOfDate();

        $this->assertInstanceOf(AbstractCondition::class, $sut);
        $this->assertInstanceOf(ConditionInterface::class, $sut);
    }

    public function testItReturnsTrueWithoutStartOrEndDate(): void
    {
        $config = [];
        $sut = $this->getInstanceOfDate();
        $this->assertTrue($sut->validate($config));
    }

    public function testItReturnsTrueWithPastStartDate(): void
    {
        $sut = $this->getInstanceOfDate();
        $this->assertTrue($sut->validate([
            'start_date' => self::PAST_DATE1,
        ]));
        $this->assertTrue($sut->validate([
            'start_date' => self::PAST_DATE2,
        ]));
        $this->assertFalse($sut->validate([
            'start_date' => self::FUTURE_DATE1,
        ]));
        $this->assertFalse($sut->validate([
            'start_date' => self::FUTURE_DATE2,
        ]));
    }

    public function testItReturnsTrueWithFutureEndDate(): void
    {
        $sut = $this->getInstanceOfDate();
        $this->assertTrue($sut->validate([
            'end_date' => self::FUTURE_DATE1,
        ]));
        $this->assertTrue($sut->validate([
            'end_date' => self::FUTURE_DATE2,
        ]));
        $this->assertFalse($sut->validate([
            'end_date' => self::PAST_DATE1,
        ]));
        $this->assertFalse($sut->validate([
            'end_date' => self::PAST_DATE2,
        ]));
    }

    public function testItReturnsTrueWhenBetweenPastStartAndFutureEndDate(): void
    {
        $sut = $this->getInstanceOfDate();
        $this->assertTrue($sut->validate([
            'start_date' => self::PAST_DATE1,
            'end_date' => self::FUTURE_DATE2,
        ]));
        $this->assertFalse($sut->validate([
            'start_date' => self::FUTURE_DATE2,
            'end_date' => self::PAST_DATE1,
        ]));
        $this->assertTrue($sut->validate([
            'start_date' => self::PAST_DATE1,
            'end_date' => self::FUTURE_DATE2,
        ]));
        $this->assertFalse($sut->validate([
            'start_date' => self::FUTURE_DATE2,
            'end_date' => self::PAST_DATE1,
        ]));
    }

    public function testToString(): void
    {
        $sut = $this->getInstanceOfDate();

        $this->assertSame('date', (string) $sut);
    }
}
