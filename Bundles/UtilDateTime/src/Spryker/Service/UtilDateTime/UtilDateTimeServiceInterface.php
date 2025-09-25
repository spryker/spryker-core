<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDateTime;

/**
 * @method \Spryker\Service\UtilDateTime\UtilDateTimeServiceFactory getFactory()
 */
interface UtilDateTimeServiceInterface
{
    /**
     * Specification:
     * - Formats a given datetime string into a configured date
     *
     * @api
     *
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatDate($date);

    /**
     * Specification:
     * - Formats a given datetime string into a configured datetime
     *
     * @api
     *
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatDateTime($date);

    /**
     * Specification:
     * - Formats a given datetime string into the ISO datetime format.
     * - If argument `timezone` is passed, returns datetime for provided timezone.
     * - If argument `timezone` is not passed, uses timezone specified in global config {@link \Spryker\Shared\UtilDateTime\UtilDateTimeConstants::DATE_TIME_ZONE}.
     * - If global config is not specified, uses "Europe/Berlin" timezone.
     *
     * @api
     *
     * @param \DateTime|string $dateTime
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateTimeToIso8601($dateTime, ?string $timezone = null): string;

    /**
     * Specification:
     * - Formats a given datetime string into the UTC ISO datetime format.
     * - If argument `timezone` is passed, returns datetime for provided timezone.
     * - If argument `timezone` is not passed, uses timezone specified in global config {@link \Spryker\Shared\UtilDateTime\UtilDateTimeConstants::DATE_TIME_ZONE}.
     * - If global config is not specified, uses "Europe/Berlin" timezone.
     *
     * @api
     *
     * @param \DateTime|string $dateTime
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateTimeToUtcIso8601($dateTime, ?string $timezone = null): string;

    /**
     * Specification:
     * - Formats a given datetime string into a configured time
     *
     * @api
     *
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatTime($date);

    /**
     * Specification:
     * - Formats a given datetime string into the custom datetime format.
     *
     * @api
     *
     * @param \DateTime|string $dateTime
     * @param string $format
     *
     * @return string
     */
    public function formatDateTimeToCustomFormat($dateTime, string $format): string;

    /**
     * Specification:
     * - Returns application timezone.
     * - One of these values in order by priority: SERVICE_TIMEZONE application service value,
     * configured value in application config or fallsback to default value defined in module config.
     *
     * @api
     *
     * @return string
     */
    public function getTimezone(): string;
}
