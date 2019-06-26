<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle\Date\Day;

use Enum\AbstractEnum;

class DayEnum extends AbstractEnum
{
    public const DAY_MONDAY = 1;
    public const DAY_TUESDAY = 2;
    public const DAY_WEDNESDAY = 3;
    public const DAY_THURSDAY = 4;
    public const DAY_FRIDAY = 5;
    public const DAY_SATURDAY = 6;
    public const DAY_SUNDAY = 7;
}
