<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle\Date;

use DateTime as DateTimePhp;
use DateInterval as DateIntervalPhp;
use Tuscanicz\DateTimeBundle\Date\Day\DayEnum;

class Date
{
    private $year;
    private $month;
    private $day;

    public function __construct(int $year, int $month, int $day)
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    public function toTimestamp(): int
    {
        $timeStamp = mktime(0, 0, 0, $this->month, $this->day, $this->year);
        if ($timeStamp === false) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Could not calculate timestamp from Date: %s/%s/%s',
                    $this->month,
                    $this->day,
                    $this->year
                )
            );
        }

        return $timeStamp;
    }

    public function toFormat(string $format): string
    {
        $stringFormat = date($format, $this->toTimestamp());
        if ($stringFormat === false) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Could not convert Date: %s/%s/%s to format: %s',
                    $this->month,
                    $this->day,
                    $this->year,
                    $format
                )
            );
        }

        return $stringFormat;
    }

    public function isWeekend(): bool
    {
        return $this->getDayOfWeek()->in([DayEnum::DAY_SUNDAY, DayEnum::DAY_SATURDAY]) === true;
    }

    public function isWorkingDay(): bool
    {
        return $this->isWeekend() === false;
    }

    public function getDayOfWeek(): DayEnum
    {
        $dayOfWeek = (int) $this->toFormat('N');
        if (DayEnum::hasValue($dayOfWeek) === true) {
            return new DayEnum($dayOfWeek);
        }

        throw new \InvalidArgumentException('Could not get day of week, invalid value given: ' . $dayOfWeek);
    }

    public function getWeek(): int
    {
        return (int) $this->toFormat('W');
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function isSameAs(Date $anotherDate): bool
    {
        return ($this->day === $anotherDate->getDay() && $this->month === $anotherDate->getMonth() && $this->year === $anotherDate->getYear());
    }

    public function toDateTime(): DateTimePhp
    {
        return new DateTimePhp($this->toFormat('r'));
    }

    public function addDays(int $days): Date
    {
        return $this->addIntervalBySpec('P' . (string) $days . 'D');
    }

    public function addMonths(int $months): Date
    {
        return $this->addIntervalBySpec('P' . (string) $months . 'M');
    }

    public function addYears(int $years): Date
    {
        return $this->addIntervalBySpec('P' . (string) $years . 'Y');
    }

    public function subDays(int $days): Date
    {
        return $this->subIntervalBySpec('P' . (string) $days . 'D');
    }

    public function subMonths(int $months): Date
    {
        return $this->subIntervalBySpec('P' . (string) $months . 'M');
    }

    public function subYears(int $years): Date
    {
        return $this->subIntervalBySpec('P' . (string) $years . 'Y');
    }

    public function getDaysFrom(Date $date): int
    {
        $startPhpDateTime = $date->toDateTime();
        $endPhpDateTime = $this->toDateTime();
        $phpDateTimeDiff = $startPhpDateTime->diff($endPhpDateTime, true);
        $startTimezoneOffset = $startPhpDateTime->getTimezone()->getOffset($startPhpDateTime);
        $endTimezoneOffset = $endPhpDateTime->getTimezone()->getOffset($startPhpDateTime);

        if ($endTimezoneOffset > $startTimezoneOffset) {
            $days = $phpDateTimeDiff->days + 1;
        } else {
            $days = $phpDateTimeDiff->days;
        }

        if ($endPhpDateTime->getTimestamp() >= $startPhpDateTime->getTimestamp()) {
            return $days;
        }

        return -$days;
    }

    public function firstDayOfMonth(): Date
    {
        return new Date($this->year, $this->month, 1);
    }

    private function addIntervalBySpec(string $intervalSpec): Date
    {
        $thisDateTime = $this->toDateTime();
        $thisDateTime->add(new DateIntervalPhp($intervalSpec));

        return $this->fromDateTimePhp($thisDateTime);
    }

    private function subIntervalBySpec(string $intervalSpec): Date
    {
        $thisDateTime = $this->toDateTime();
        $thisDateTime->sub(new DateIntervalPhp($intervalSpec));

        return $this->fromDateTimePhp($thisDateTime);
    }

    private function fromDateTimePhp(DateTimePhp $dateTimePhp): Date
    {
        return new Date(
            (int) $dateTimePhp->format('Y'),
            (int) $dateTimePhp->format('m'),
            (int) $dateTimePhp->format('d')
        );
    }
}
