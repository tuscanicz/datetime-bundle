<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle\Date;

use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    public function testToTimestamp(): void
    {
        $date = new Date(2017, 9, 28);

        self::assertSame(1506556800, $date->toTimestamp());
    }

    /**
     * @param Date $startDate
     * @param Date $endDate
     * @param int $expectedDaysDifference
     * @dataProvider getDaysFromDataProvider
     */
    public function testGetDaysFrom(Date $startDate, Date $endDate, int $expectedDaysDifference): void
    {
        $daysFrom = $endDate->getDaysFrom($startDate);

        self::assertSame($expectedDaysDifference, $daysFrom);
    }

    /**
     * @param \Tuscanicz\DateTimeBundle\Date\Date $date
     * @param \Tuscanicz\DateTimeBundle\Date\Date $expectedDate
     * @dataProvider firstDayOfMonthDataProvider
     */
    public function testFirstDayOfMonth(Date $date, Date $expectedDate): void
    {
        self::assertEquals(
            $date->firstDayOfMonth(),
            $expectedDate
        );
    }

    public function testAddDaysWithTimechange(): void
    {
        $dateTime = new Date(2010, 10, 31);

        $newDateTime = $dateTime->addDays(1);

        $expectedNewDateTime = new Date(2010, 11, 1);

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testSubDays(): void
    {
        $dateTime = new Date(1987, 7, 31);

        $newDateTime = $dateTime->subDays(15);

        $expectedNewDateTime = new Date(1987, 7, 16);

        $this->assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testAddYears(): void
    {
        $dateTime = new Date(2014, 1, 31);

        $newDateTime = $dateTime->addYears(4);

        $expectedNewDateTime = new Date(2018, 1, 31);

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testAddMonths(): void
    {
        $dateTime = new Date(2014, 1, 31);

        $newDateTime = $dateTime->addMonths(1);

        $expectedNewDateTime = new Date(2014, 3, 3);

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testSubYears(): void
    {
        $dateTime = new Date(2010, 11, 1);

        $newDateTime = $dateTime->subYears(5);

        $expectedNewDateTime = new Date(2005, 11, 1);

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function testSubMonths(): void
    {
        $dateTime = new Date(2010, 11, 1);

        $newDateTime = $dateTime->subMonths(5);

        $expectedNewDateTime = new Date(2010, 6, 1);

        self::assertEquals($expectedNewDateTime, $newDateTime);
    }

    public function getDaysFromDataProvider(): array
    {
        return [
            [new Date(2014, 1, 1), new Date(2014, 1, 1), 0],
            [new Date(2014, 1, 1), new Date(2014, 1, 2), 1],
            [new Date(2014, 1, 11), new Date(2014, 1, 28), 17],
            [new Date(2014, 9, 20), new Date(2015, 1, 2), 104], // time change (summer time -> winter time) occured
            [new Date(2015, 1, 2), new Date(2014, 9, 20), -104], // time change (wintertime -> summer time) occured
            [new Date(2014, 1, 28), new Date(2014, 1, 11), -17],
            [new Date(2016, 3, 26), new Date(2016, 3, 28), 2], // time change (winter time -> summer time) occured
            [new Date(2016, 3, 28), new Date(2016, 3, 26), -2], // time change (summer time -> winter time) occured
            [new Date(2015, 1, 1), new Date(2016, 1, 1), 365],
            [new Date(2016, 1, 1), new Date(2017, 1, 1), 366],
        ];
    }

    public function firstDayOfMonthDataProvider(): array
    {
        return [
            [new Date(2014, 1, 1), new Date(2014, 1, 1)],
            [new Date(2014, 12, 31), new Date(2014, 12, 1)],
        ];
    }
}
