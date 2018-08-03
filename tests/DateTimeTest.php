<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle;

use DateTime as DateTimePhp;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tuscanicz\DateTimeBundle\Date\Date;
use Tuscanicz\DateTimeBundle\Time\Time;

class DateTimeTest extends TestCase
{
    public function testGetters(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 59)
        );

        self::assertSame(Date::DAY_FRIDAY, $dateTime->getDate()->getDayOfWeek());
        self::assertSame(31, $dateTime->getDate()->getWeek());
        self::assertSame(1987, $dateTime->getDate()->getYear());
        self::assertSame(7, $dateTime->getDate()->getMonth());
        self::assertSame(31, $dateTime->getDate()->getDay());
        self::assertSame(11, $dateTime->getTime()->getHour());
        self::assertSame(19, $dateTime->getTime()->getMinute());
        self::assertSame(59, $dateTime->getTime()->getSecond());
    }

    public function testToFormat(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 59)
        );

        self::assertEquals('1987-07-31 11:19:59', $dateTime->toFormat('Y-m-d H:i:s'));
    }

    /**
     * @param DateTime $inDate
     * @param DateTime $outExpectedDate
     * @param int $move
     * @dataProvider addWorkingDaysDataProvider
     */
    public function testAddWorkingDays(DateTime $inDate, DateTime $outExpectedDate, int $move): void
    {
        self::assertEquals($outExpectedDate, $inDate->addWorkingDays($move));
    }

    public function addWorkingDaysDataProvider(): array
    {
        return [
            [new DateTime(new Date(2013, 6, 20), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 21), new Time(11, 19, 59)), 1],
            [new DateTime(new Date(2013, 6, 21), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), 1],
            [new DateTime(new Date(2013, 6, 22), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), 1],
            [new DateTime(new Date(2013, 6, 23), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 25), new Time(11, 19, 59)), 1],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 25), new Time(11, 19, 59)), 1],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 28), new Time(11, 19, 59)), 4],
            [new DateTime(new Date(2013, 6, 23), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 28), new Time(11, 19, 59)), 4],
            [new DateTime(new Date(2013, 6, 22), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 28), new Time(11, 19, 59)), 4],
            [new DateTime(new Date(2013, 6, 21), new Time(11, 19, 59)), new DateTime(new Date(2013, 6, 27), new Time(11, 19, 59)), 4],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 7, 1), new Time(11, 19, 59)), 5],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 7, 8), new Time(11, 19, 59)), 10],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 7, 15), new Time(11, 19, 59)), 15],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 7, 22), new Time(11, 19, 59)), 20],
            [new DateTime(new Date(2013, 6, 24), new Time(11, 19, 59)), new DateTime(new Date(2013, 7, 29), new Time(11, 19, 59)), 25],
        ];
    }

    public function testToTimestamp(): void
    {
        $dateTime = new DateTime(new Date(2009, 2, 13), new Time(23, 31, 30));

        self::assertSame(
            1234567890,
            $dateTime->toTimestamp()
        );
    }

    /**
     * @param DateTime $now
     * @param DateTime $start
     * @param DateTime $end
     * @param bool $expectedResult
     * @dataProvider isBetweenProvider
     */
    public function testIsBetween(DateTime $now, DateTime $start, DateTime $end, bool $expectedResult): void
    {
        self::assertSame($expectedResult, $now->isBetween($start, $end));
    }

    public function isBetweenProvider(): array
    {
        return [
            [new DateTime(new Date(2012, 5, 5), new Time(4, 5, 6)), new DateTime(new Date(2011, 5, 5), new Time(4, 5, 6)), new DateTime(new Date(2013, 5, 5), new Time(4, 5, 6)), true],
            [new DateTime(new Date(2015, 5, 5), new Time(4, 5, 6)), new DateTime(new Date(2011, 5, 5), new Time(4, 5, 6)), new DateTime(new Date(2013, 5, 5), new Time(4, 5, 6)), false],
            [new DateTime(new Date(2010, 5, 5), new Time(4, 5, 6)), new DateTime(new Date(2011, 5, 5), new Time(4, 5, 6)), new DateTime(new Date(2013, 5, 5), new Time(4, 5, 6)), false],
        ];
    }
    /**
     * @param DateTime $oneDateTime
     * @param DateTime $secondDateTime
     * @param bool $expectedResult
     * @dataProvider isSameAsDataProvider
     */
    public function testIsSameAs(DateTime $oneDateTime, DateTime $secondDateTime, bool $expectedResult): void
    {
        self::assertSame($expectedResult, $oneDateTime->isSameAs($secondDateTime));
    }

    public function isSameAsDataProvider(): array
    {
        return [
            [
                new DateTime(
                    new Date(2012, 5, 5),
                    new Time(4, 5, 7)
                ),
                new DateTime(
                    new Date(2011,5, 5),
                    new Time(4, 5, 6)
                ),
                false,
            ],
            [
                new DateTime(
                    new Date(2012, 5, 5),
                    new Time(4, 5, 7)
                ),
                new DateTime(
                    new Date(2012,5, 5),
                    new Time(4, 5, 6)
                ),
                false,
            ],
            [
                new DateTime(
                    new Date(2012, 5, 5),
                    new Time(4, 5, 6)
                ),
                new DateTime(
                    new Date(2012,5, 5),
                    new Time(4, 5, 6)
                ),
                true,
            ],
        ];
    }

    public function testSubYears(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->subYears(15);

        $expectedNewDateTime = new DateTime(
            new Date(1972, 7, 31),
            new Time(11, 19, 0)
        );

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testSubDays(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->subDays(15);

        $expectedNewDateTime = new DateTime(
            new Date(1987, 7, 16),
            new Time(11, 19, 0)
        );

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testSubMonths(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->subMonths(10);

        $expectedNewDateTime = new DateTime(
            new Date(1986, 10, 1),
            new Time(11, 19, 0)
        );

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testSubHours(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->subHours(2);

        $expectedNewDateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(9, 19, 0)
        );

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testSubMinutes(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->subMinutes(5);

        $expectedNewDateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 14, 0)
        );

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testAddDays(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->addDays(15);

        $expectedNewDateTime = new DateTime(
            new Date(1987, 8, 15),
            new Time(11, 19, 0)
        );

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testAddMonths(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->addMonths(10);

        $expectedNewDateTime = new DateTime(
            new Date(1988, 5, 31),
            new Time(11, 19, 0)
        );

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testAddYears(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->addYears(15);

        $expectedNewDateTime = new DateTime(
            new Date(2002, 7, 31),
            new Time(11, 19, 0)
        );

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testAddHours(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->addHours(2);

        $expectedNewDateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(13, 19, 0)
        );

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testAddMinutes(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $newDateTime = $dateTime->addMinutes(80);

        $expectedNewDateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(12, 39, 0)
        );

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testToDateTime(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 31),
            new Time(11, 19, 0)
        );

        $actualPhpDateTime = $dateTime->toDateTime();
        $expectedNewDateTime = new DateTimePhp('1987-07-31 11:19:00');

        self::assertEquals($expectedNewDateTime, $actualPhpDateTime);
    }

    public function testToDateTimeImmutable(): void
    {
        $dateTime = new DateTime(
            new Date(1987, 7, 3),
            new Time(11, 19, 3)
        );

        $actualPhpDateTime = $dateTime->toDateTimeImmutable();
        $expectedNewDateTime = new DateTimeImmutable('1987-07-03 11:19:03');

        self::assertEquals($expectedNewDateTime, $actualPhpDateTime);
    }
}
