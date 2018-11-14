<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle;

use DateInterval;
use DateTime as DateTimePhp;
use DateTimeImmutable;
use DateTimeZone;
use Tuscanicz\DateTimeBundle\Date\Date;
use Tuscanicz\DateTimeBundle\Time\Time;

class DateTime
{
    private $date;
    private $time;
    private $timezone;

    public function __construct(Date $date, Time $time, string $timezone = DateTimeFactory::TIMEZONE_GMT)
    {
        $this->date = $date;
        $this->time = $time;
        $this->timezone = $timezone;
    }

    public function toTimestamp(): int
    {
        return $this->createDateTimePhp()->getTimestamp();
    }

    public function getDate(): Date
    {
        return $this->date;
    }

    public function getTime(): Time
    {
        return $this->time;
    }

    public function isSameAs(DateTime $anotherDateTime): bool
    {
        return $this->date->isSameAs($anotherDateTime->getDate()) && $this->time->isSameAs($anotherDateTime->getTime());
    }

    public function toFormat(string $format): string
    {
        return $this->createDateTimePhp()->format($format);
    }

    public function toDateTime(): DateTimePhp
    {
        return new DateTimePhp($this->toFormat('r'));
    }

    public function toDateTimeImmutable(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromMutable($this->toDateTime());
    }

    public function isBetween(DateTime $start, DateTime $end): bool
    {
        $thisDateTime = $this->toDateTime();

        return $thisDateTime < $end->toDateTime() ? $thisDateTime > $start->toDateTime() : false;
    }

    public function addYears(int $years): DateTime
    {
        return $this->addIntervalBySpec('P' . (string) $years . 'Y');
    }

    public function addMonths(int $months): DateTime
    {
        return $this->addIntervalBySpec('P' . (string) $months . 'M');
    }

    public function addDays(int $days): DateTime
    {
        return $this->addIntervalBySpec('P' . (string) $days . 'D');
    }

    public function addHours(int$hours): DateTime
    {
        return $this->addIntervalBySpec('PT' . (string) $hours . 'H');
    }

    public function addMinutes(int $minutes): DateTime
    {
        return $this->addIntervalBySpec('PT' . (string) $minutes . 'M');
    }

    public function addWorkingDays(int $days): DateTime
    {
        $weekendDays = ((int)($days / 5) * 2);
        $totalAddedDays = $days + $weekendDays;

        $thisDateTime = $this->toDateTime();
        $thisDateTime->add(new DateInterval('P' . (string) $totalAddedDays . 'D'));

        if ($thisDateTime->format('N') < $this->toDateTime()->format('N')) {
            $thisDateTime->add(new DateInterval('P' . ((int)$this->toDateTime()->format('N') === 7 ? 1 : 2) . 'D'));
        }

        if ((int)$thisDateTime->format('N') === 7) {
            $thisDateTime->add(new DateInterval('P' . 1 . 'D'));
        }

        if ((int)$thisDateTime->format('N') === 6) {
            $thisDateTime->add(new DateInterval('P' . 2 . 'D'));
        }

        return $this->fromDateTimePhp($thisDateTime);
    }

    public function subYears(int $years): DateTime
    {
        return $this->subIntervalBySpec('P' . (string) $years . 'Y');
    }

    public function subMonths(int $months): DateTime
    {
        return $this->subIntervalBySpec('P' . (string) $months . 'M');
    }

    public function subDays(int $days): DateTime
    {
        return $this->subIntervalBySpec('P' . (string) $days . 'D');
    }

    public function subHours(int $hours): DateTime
    {
        return $this->subIntervalBySpec('PT' . (string) $hours . 'H');
    }

    public function subMinutes(int $minutes): DateTime
    {
        return $this->subIntervalBySpec('PT' . (string) $minutes . 'M');
    }

    public function isAfter(DateTime $dateTime): bool
    {
        $currentTimestamp = $this->toTimestamp();
        $dateTimeTimestamp = $dateTime->toTimestamp();

        $diffInSeconds = $currentTimestamp - $dateTimeTimestamp;

        return $diffInSeconds > 0;
    }

    public function isBefore(DateTime $dateTime): bool
    {
        $currentTimestamp = $this->toTimestamp();
        $dateTimeTimestamp = $dateTime->toTimestamp();

        $diffInSeconds = $dateTimeTimestamp - $currentTimestamp;

        return $diffInSeconds > 0;
    }

    private function subIntervalBySpec(string $intervalSpec): DateTime
    {
        $thisDateTime = $this->toDateTime();
        $thisDateTime->sub(new DateInterval($intervalSpec));

        return $this->fromDateTimePhp($thisDateTime);
    }

    private function addIntervalBySpec(string $intervalSpec): DateTime
    {
        $thisDateTime = $this->toDateTime();
        $thisDateTime->add(new DateInterval($intervalSpec));

        return $this->fromDateTimePhp($thisDateTime);
    }

    private function fromDateTimePhp(DateTimePhp $dateTimePhp): DateTime
    {
        return new self(
            new Date(
                (int) $dateTimePhp->format('Y'),
                (int) $dateTimePhp->format('m'),
                (int) $dateTimePhp->format('d')
            ),
            new Time(
                (int) $dateTimePhp->format('H'),
                (int) $dateTimePhp->format('i'),
                (int) $dateTimePhp->format('s')
            )
        );
    }

    private function createDateTimePhp(): DateTimePhp
    {
        $timezonePhp = new DateTimeZone($this->timezone);

        $datetimePhp = new DateTimePhp('now', $timezonePhp);
        $datetimePhp->setDate($this->date->getYear(), $this->date->getMonth(), $this->date->getDay());
        $datetimePhp->setTime($this->time->getHour(), $this->time->getMinute(), $this->time->getSecond());

        return $datetimePhp;
    }
}
