<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle\Date\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Tuscanicz\DateTimeBundle\Date\Date;

class DateType extends Type
{
    const TUSCANICZ_DATETIME = 'tuscaniczDate';

    /**
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDateTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param Date|null $value
     * @param AbstractPlatform $platform
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? $value->toFormat($platform->getDateFormatString()) : null;
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
     * @throws ConversionException
     * @return Date|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof \DateTime) {
            return $value;
        }

        $dateTime = date_create_from_format($platform->getDateFormatString(), $value);
        if ($dateTime === false) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateFormatString());
        }

        $timestamp = $dateTime->getTimestamp();

        return new Date(
            (int) date('Y', $timestamp),
            (int) date('m', $timestamp),
            (int) date('d', $timestamp)
        );
    }

    /**
     * @return int
     */
    public function getBindingType()
    {
        return \PDO::PARAM_STR;
    }
}
