<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle\Time;

use PHPUnit\Framework\TestCase;

class TimeTest extends TestCase
{
    public function testToTimestamp(): void
    {
        $time = new Time(10, 9, 28);

        self::assertSame('10:09:28', $time->toFormat('H:i:s'));
    }
}
