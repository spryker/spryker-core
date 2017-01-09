<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library;

use DateTime;
use DateTimeZone;
use Spryker\Shared\Library\Exception\UnsupportedDateFormatException;

class DateFormatter implements DateFormatterInterface
{

    const DATE_FORMAT_SHORT = 'short';
    const DATE_FORMAT_MEDIUM = 'medium';
    const DATE_FORMAT_RFC = 'rfc';
    const DATE_FORMAT_DATETIME = 'datetime';

    /**
     * @var \Spryker\Shared\Library\Context
     */
    private $context;

    /**
     * @param \Spryker\Shared\Library\Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param string $date
     * @param \DateTimeZone|null $timezone
     *
     * @return string
     */
    public function dateShort($date, DateTimeZone $timezone = null)
    {
        return $this->formatDate($date, self::DATE_FORMAT_SHORT, $timezone);
    }

    /**
     * @param string $date
     * @param \DateTimeZone|null $timezone
     *
     * @return string
     */
    public function dateMedium($date, DateTimeZone $timezone = null)
    {
        return $this->formatDate($date, self::DATE_FORMAT_MEDIUM, $timezone);
    }

    /**
     * @param string $date
     * @param \DateTimeZone|null $timezone
     *
     * @return string
     */
    public function dateRFC($date, DateTimeZone $timezone = null)
    {
        return $this->formatDate($date, self::DATE_FORMAT_RFC, $timezone);
    }

    /**
     * @param string $date
     * @param \DateTimeZone|null $timezone
     *
     * @return string
     */
    public function dateTime($date, DateTimeZone $timezone = null)
    {
        return $this->formatDate($date, self::DATE_FORMAT_DATETIME, $timezone);
    }

    /**
     * @param \DateTime|string $date
     * @param string $dateFormat
     * @param \DateTimeZone|null $timezone
     *
     * @throws \Spryker\Shared\Library\Exception\UnsupportedDateFormatException
     *
     * @return string|null
     */
    protected function formatDate($date, $dateFormat, DateTimeZone $timezone = null)
    {
        if (empty($date)) {
            return null;
        }

        if (!array_key_exists($dateFormat, $this->context->dateFormat)) {
            throw new UnsupportedDateFormatException(sprintf('Unsupported date format: %s', $dateFormat));
        }

        if ($timezone === null) {
            return $this->context->dateTimeConvertTo($date, $this->context->dateFormat[$dateFormat]);
        }

        if (!($date instanceof DateTime)) {
            $date = new DateTime($date, $timezone);
        } else {
            $date->setTimezone($timezone);
        }

        return $date->format($this->context->dateFormat[$dateFormat]);
    }

}
