<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle\Date;

class DateInterval
{
    private $from;
    private $to;

    public function __construct(Date $from, Date $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function getFrom(): Date
    {
        return $this->from;
    }

    public function getTo(): Date
    {
        return $this->to;
    }

    public function getLengthInDays(): int
    {
        $startPhpDateTime = $this->from->toDateTime();
        $endPhpDateTime = $this->to->toDateTime();
        $phpDateTimeDiff = $startPhpDateTime->diff($endPhpDateTime, true);
        if ($phpDateTimeDiff === false) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Could not calculate length in days from Dates: %s and %s',
                    $this->from->toFormat('d/m/Y'),
                    $this->to->toFormat('d/m/Y')
                )
            );
        }

        return $phpDateTimeDiff->days;
    }
}
