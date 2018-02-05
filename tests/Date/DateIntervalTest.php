<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle\Date;

use PHPUnit\Framework\TestCase;

class DateIntervalTest extends TestCase
{
    public function testGetLengthInDays(): void
    {
        $dateInterval = new DateInterval(new Date(2013, 10, 5), new Date(2014, 10, 5));

        self::assertSame(365, $dateInterval->getLengthInDays());
        self::assertInstanceOf(Date::class, $dateInterval->getFrom());
        self::assertInstanceOf(Date::class, $dateInterval->getTo());
    }
}
