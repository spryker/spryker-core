<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDateTime\Model;

interface DateTimeFormatterInterface
{
    /**
     * @param \DateTime|string $dateTime
     *
     * @return string
     */
    public function formatDate($dateTime);

    /**
     * @param \DateTime|string $dateTime
     *
     * @return string
     */
    public function formatTime($dateTime);

    /**
     * @param \DateTime|string $dateTime
     *
     * @return string
     */
    public function formatDateTime($dateTime);

    /**
     * @param \DateTime|string $dateTime
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateTimeToIso8601($dateTime, ?string $timezone = null): string;

    /**
     * @param \DateTime|string $dateTime
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateTimeToUtcIso8601($dateTime, ?string $timezone = null): string;

    /**
     * @param \DateTime|string $dateTime
     * @param string $format
     *
     * @return string
     */
    public function formatDateTimeToCustomFormat($dateTime, string $format): string;
}
