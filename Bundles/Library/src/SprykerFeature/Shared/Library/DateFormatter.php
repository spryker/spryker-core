<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library;

use SprykerFeature\Shared\Library\Exception\UnsupportedDateFormatException;

class DateFormatter
{
    const DEFAULT_TIMEZONE = 'UTC';
    const DATE_FORMAT_SHORT = 'short';
    const DATE_FORMAT_MEDIUM = 'medium';
    const DATE_FORMAT_RFC = 'rfc';
    const DATE_FORMAT_DATETIME = 'datetime';

    /**
     * @param string $date
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param \DateTimeZone $timezone
     *
     * @return string
     */
    public function dateShort($date, $context = null, \DateTimeZone $timezone = null)
    {
        return $this->formatDate($date, self::DATE_FORMAT_SHORT, $context, $timezone);
    }

    /**
     * @param string $date
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param \DateTimeZone $timezone
     *
     * @return string
     */
    public function dateMedium($date, $context = null, \DateTimeZone $timezone = null)
    {
        return $this->formatDate($date, self::DATE_FORMAT_MEDIUM, $context, $timezone);
    }

    /**
     * @param string $date
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param \DateTimeZone $timezone
     *
     * @return string
     */
    public function dateRFC($date, $context = null, \DateTimeZone $timezone = null)
    {
        return $this->formatDate($date, self::DATE_FORMAT_RFC, $context, $timezone);
    }

    /**
     * @param string $date
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param \DateTimeZone $timezone
     *
     * @return string
     */
    public function dateTime($date, $context = null, \DateTimeZone $timezone = null)
    {
        return $this->formatDate($date, self::DATE_FORMAT_DATETIME, $context, $timezone);
    }

    /**
     * @param \DateTime|string $date
     * @param string $dateFormat
     * @param \SprykerFeature_Shared_Library_Context|string|null $context
     * @param \DateTimeZone $timezone
     *
     * @throws \SprykerFeature\Shared\Library\Exception\UnsupportedDateFormatException
     *
     * @return string
     */
    protected function formatDate($date, $dateFormat, $context = null, \DateTimeZone $timezone = null)
    {
        $context = $this->getContext($context);

        if (!isset($context->dateFormat[$dateFormat])) {
            throw new UnsupportedDateFormatException(sprintf('Unsupported date format: %s', $dateFormat));
        }

        if ($timezone === null) {
            return $context->dateTimeConvertTo($date, $context->dateFormat[$dateFormat]);
        }

        if (!($date instanceof \DateTime)) {
            $date = new \DateTime($date, $timezone);
        } else {
            $date->setTimezone($timezone);
        }

        return $date->format($context->dateFormat[$dateFormat]);
    }

    /**
     * @param \SprykerFeature_Shared_Library_Context|string $context
     *
     * @return \SprykerFeature_Shared_Library_Context
     */
    protected function getContext($context)
    {
        return \SprykerFeature_Shared_Library_Context::getInstance($context);
    }

}
