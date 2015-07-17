<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
class SprykerFeature_Shared_Library_Date extends \SprykerEngine\Shared\Kernel\Store
{

    const DATE_FORMAT_SHORT = 'short';
    const DATE_FORMAT_MEDIUM = 'medium';
    const DATE_FORMAT_RFC = 'rfc';
    const DATE_FORMAT_DATETIME = 'datetime';

    /**
     * @param string $date
     * @param string $dateFormat
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param bool $convertTz
     *
     * @throws Exception
     *
     * @return string
     */
    protected static function formatDate($date, $dateFormat, $context = null, $convertTz = true)
    {
        $context = \SprykerFeature_Shared_Library_Context::getInstance($context);
        if (!isset($context->dateFormat[$dateFormat])) {
            throw new Exception('Unsupported date format: ' . $dateFormat);
        }
        if ($convertTz) {
            return $context->dateTimeConvertTo($date, $context->dateFormat[$dateFormat]);
        }
        if (!($date instanceof DateTime)) {
            $date = new DateTime($date);
        }

        return $date->format($context->dateFormat[$dateFormat]);
    }

    /**
     * @static
     *
     * @param string $date
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param bool $convertTz should date/time be converted to context's timezone
     *
     * @throws Exception
     *
     * @return string
     */
    public static function dateShort($date, $context = null, $convertTz = true)
    {
        return self::formatDate($date, self::DATE_FORMAT_SHORT, $context, $convertTz);
    }

    /**
     * @static
     *
     * @param string $date
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param bool $convertTz should date/time be converted to context's timezone
     *
     * @throws Exception
     *
     * @return string
     */
    public static function dateMedium($date, $context = null, $convertTz = true)
    {
        return self::formatDate($date, self::DATE_FORMAT_MEDIUM, $context, $convertTz);
    }

    /**
     * @static
     *
     * @param string $date
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param bool $convertTz should date/time be converted to context's timezone
     *
     * @throws Exception
     *
     * @return string
     */
    public static function dateRFC($date, $context = null, $convertTz = true)
    {
        return self::formatDate($date, self::DATE_FORMAT_RFC, $context, $convertTz);
    }

    /**
     * @static
     *
     * @param string $date
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param bool $convertTz should date/time be converted to context's timezone
     *
     * @throws Exception
     *
     * @return string
     */
    public static function dateTime($date, $context = null, $convertTz = true)
    {
        return self::formatDate($date, self::DATE_FORMAT_DATETIME, $context, $convertTz);
    }

}
