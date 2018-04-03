<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle;

use DateTime as DateTimePhp;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tuscanicz\DateTimeBundle\Date\Date;
use Tuscanicz\DateTimeBundle\Time\Time;

class DateTimeFactoryTest extends TestCase
{
    const TIMEZONE_GMT = 'GMT';
    const TIMEZONE_PRAGUE = 'Europe/Prague';
    const TIMEZONE_LOS_ANGELES = 'America/Los_Angeles';

    /** @var DateTimeFactory */
    private $dateTimeFactory;

    protected function setUp(): void
    {
        $this->dateTimeFactory = new DateTimeFactory();
    }

    public function testNow(): void
    {
        $dateTime = $this->dateTimeFactory->now(self::TIMEZONE_GMT);

        self::assertInstanceOf(DateTime::class, $dateTime);
    }

    public function testFromTimestamp(): void
    {
        $expectedDateTime = new DateTime(new Date(2009, 2, 13), new Time(23, 31, 30));
        $dateTime = $this->dateTimeFactory->fromTimestamp(1234567890, self::TIMEZONE_GMT);

        self::assertEquals($expectedDateTime, $dateTime);
    }

    public function testFromDateTimeInterfaceWithDateTime(): void
    {
        $expectedDateTime = new DateTime(new Date(2009, 2, 13), new Time(23, 31, 30));
        $dateTime = $this->dateTimeFactory->fromDateTimeInterface(
            new DateTimePhp('2009-02-13 23:31:30'),
            self::TIMEZONE_GMT
        );

        self::assertEquals($expectedDateTime, $dateTime);
    }

    public function testFromDateTimeInterfaceWithDateTimeImmutable(): void
    {
        $expectedDateTime = new DateTime(new Date(2009, 2, 13), new Time(23, 31, 30));
        $dateTime = $this->dateTimeFactory->fromDateTimeInterface(
            new DateTimeImmutable('2009-02-13 23:31:30'),
            self::TIMEZONE_GMT
        );

        self::assertEquals($expectedDateTime, $dateTime);
    }
}
