<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Tuscanicz\DateTimeBundle\Date\Date;
use Tuscanicz\DateTimeBundle\DateTime;
use Tuscanicz\DateTimeBundle\Time\Time;

class DateTimeType extends Type
{
    const TUSCANICZ_DATETIME = 'tuscaniczDateTime';

    /**
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDateTimeTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param DateTime $value
     * @param AbstractPlatform $platform
     * @return null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? $value->toFormat($platform->getDateTimeFormatString()) : null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::TUSCANICZ_DATETIME;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed|DateTime
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof \DateTime) {
            return $value;
        }

        $dateTime = date_create_from_format($platform->getDateTimeFormatString(), $value);
        if ($dateTime === false) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeFormatString());
        }

        $timestamp = $dateTime->getTimestamp();

        return new DateTime(
            new Date(
                date('Y', $timestamp),
                date('m', $timestamp),
                date('d', $timestamp)
            ),
            new Time(
                date('H', $timestamp),
                date('i', $timestamp),
                date('s', $timestamp) + ($timestamp - (int)$timestamp)
            )
        );
    }

    /**
     * @return int
     */
    public function getBindingType()
    {
        return \PDO::PARAM_STR;
    }

    /**
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
