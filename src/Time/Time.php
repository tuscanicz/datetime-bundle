<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle\Time;

class Time
{
    private $hour;
    private $minute;
    private $second;

    public function __construct(int $hour, int $minute, int $second)
    {
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function getMinute(): int
    {
        return $this->minute;
    }

    public function getSecond(): int
    {
        return $this->second;
    }

    public function toFormat(string $format): string
    {
        $timeStamp = mktime(
            $this->hour,
            $this->minute,
            $this->second
        );
        if ($timeStamp !== false) {
            $date = date(
                $format,
                $timeStamp
            );
            if ($date !== false) {
                return $date;
            }
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Could not convert Time: %s:%s:%s',
                $this->hour,
                $this->minute,
                $this->second
            )
        );
    }

    public function isSameAs(Time $anotherTime): bool
    {
        return ($this->second === $anotherTime->getSecond() && $this->minute === $anotherTime->getMinute() && $this->hour === $anotherTime->getHour());
    }
}
