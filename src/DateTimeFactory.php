<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle;

use DateTime as DateTimePhp;
use DateTimeZone;
use Tuscanicz\DateTimeBundle\Date\Date;
use Tuscanicz\DateTimeBundle\Time\Time;

class DateTimeFactory
{
    const TIMEZONE_GMT = 'GMT';
    const FORMAT_DATE_ONLY = 'Y-m-d';
    const FORMAT_DATETIME = 'Y-m-d H:i:s';

    public function now(string $timezone = self::TIMEZONE_GMT): DateTime
    {
        $timezonePhp = new DateTimeZone($timezone);
        $datetimePhp = new DateTimePhp('now', $timezonePhp);

        return $this->fromDateTimePhp($datetimePhp, $timezone);
    }

    public function fromTimestamp(int $timestamp, string $timezone = self::TIMEZONE_GMT): DateTime
    {
        $timezonePhp = new DateTimeZone($timezone);
        $datetimePhp = new DateTimePhp('now', $timezonePhp);
        $datetimePhp->setTimestamp($timestamp);

        return $this->fromDateTimePhp($datetimePhp, $timezone);
    }

    private function fromDateTimePhp(DateTimePhp $dateTimePhp, string $timezone = DateTimeFactory::TIMEZONE_GMT): DateTime
    {
        return new DateTime(
            new Date(
                (int) $dateTimePhp->format('Y'),
                (int) $dateTimePhp->format('m'),
                (int) $dateTimePhp->format('d')
            ),
            new Time(
                (int) $dateTimePhp->format('H'),
                (int) $dateTimePhp->format('i'),
                (int) $dateTimePhp->format('s')
            ),
            $timezone
        );
    }
}
