<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDateTime\Model;

use DateTime;
use DateTimeZone;
use Spryker\Shared\Config\Config;
use Spryker\Shared\UtilDateTime\UtilDateTimeConstants;

class DateTimeFormatter implements DateTimeFormatterInterface
{
    const DEFAULT_TIME_ZONE = 'Europe/Berlin';
    const DEFAULT_FORMAT_TIME = 'H:i';
    const DEFAULT_FORMAT_DATE_TIME = 'M. d, Y H:i';
    const DEFAULT_FORMAT_DATE = 'M. d, Y';

    /**
     * @var \Spryker\Shared\Config\Config
     */
    protected $config;

    /**
     * @param \Spryker\Shared\Config\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param \DateTime|string $dateTime
     *
     * @return string
     */
    public function formatDate($dateTime)
    {
        return $this->format($dateTime, UtilDateTimeConstants::DATE_TIME_FORMAT_DATE, static::DEFAULT_FORMAT_DATE);
    }

    /**
     * @param \DateTime|string $dateTime
     *
     * @return string
     */
    public function formatDateTime($dateTime)
    {
        return $this->format($dateTime, UtilDateTimeConstants::DATE_TIME_FORMAT_DATE_TIME, static::DEFAULT_FORMAT_DATE_TIME);
    }

    /**
     * @param \DateTime|string $dateTime
     *
     * @return string
     */
    public function formatTime($dateTime)
    {
        return $this->format($dateTime, UtilDateTimeConstants::DATE_TIME_FORMAT_TIME, static::DEFAULT_FORMAT_TIME);
    }

    /**
     * @param \DateTime|string $dateTime
     * @param string $formatConfigConstant
     * @param string $defaultFormat
     *
     * @return string|null
     */
    protected function format($dateTime, $formatConfigConstant, $defaultFormat)
    {
        $dateTimeZone = new DateTimeZone($this->config->get(UtilDateTimeConstants::DATE_TIME_ZONE, static::DEFAULT_TIME_ZONE));
        $configuredFormat = $this->config->get($formatConfigConstant, $defaultFormat);

        if (!($dateTime instanceof DateTime)) {
            $dateTime = new DateTime($dateTime);
        }

        $dateTime->setTimezone($dateTimeZone);

        return $dateTime->format($configuredFormat);
    }
}
