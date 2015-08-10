<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library;

use SprykerFeature\Shared\Library\Exception\UnsupportedDateFormatException;

class DateFormatter
{
    const DATE_FORMAT_SHORT = 'short';
    const DATE_FORMAT_MEDIUM = 'medium';
    const DATE_FORMAT_RFC = 'rfc';
    const DATE_FORMAT_DATETIME = 'datetime';

    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param string $date
     * @param \DateTimeZone $timezone
     *
     * @return string
     */
    public function dateShort($date, \DateTimeZone $timezone = null)
    {
        return $this->formatDate($date, self::DATE_FORMAT_SHORT, $timezone);
    }

    /**
     * @param string $date
     * @param \DateTimeZone $timezone
     *
     * @return string
     */
    public function dateMedium($date, \DateTimeZone $timezone = null)
    {
        return $this->formatDate($date, self::DATE_FORMAT_MEDIUM, $timezone);
    }

    /**
     * @param string $date
     * @param \DateTimeZone $timezone
     *
     * @return string
     */
    public function dateRFC($date, \DateTimeZone $timezone = null)
    {
        return $this->formatDate($date, self::DATE_FORMAT_RFC, $timezone);
    }

    /**
     * @param string $date
     * @param \DateTimeZone $timezone
     *
     * @return string
     */
    public function dateTime($date, \DateTimeZone $timezone = null)
    {
        return $this->formatDate($date, self::DATE_FORMAT_DATETIME, $timezone);
    }

    /**
     * @param \DateTime|string $date
     * @param string $dateFormat
     * @param \DateTimeZone $timezone
     *
     * @throws \SprykerFeature\Shared\Library\Exception\UnsupportedDateFormatException
     *
     * @return string
     */
    protected function formatDate($date, $dateFormat, \DateTimeZone $timezone = null)
    {
        if (!isset($this->context->dateFormat[$dateFormat])) {
            throw new UnsupportedDateFormatException(sprintf('Unsupported date format: %s', $dateFormat));
        }

        if (null === $timezone) {
            return $this->context->dateTimeConvertTo($date, $this->context->dateFormat[$dateFormat]);
        }

        if (!($date instanceof \DateTime)) {
            $date = new \DateTime($date, $timezone);
        } else {
            $date->setTimezone($timezone);
        }

        return $date->format($this->context->dateFormat[$dateFormat]);
    }

}
