<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AvailabilityNotification;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface AvailabilityNotificationConstants
{
    /**
     * Specification:
     * - Defines stores to Yves host mapping.
     *
     * @api
     *
     * @example The format of returned array is:
     * [
     *    'DE' => 'yves.de.spryker.local',
     *    'AT' => 'yves.at.spryker.local',
     * ]
     *
     * @var string
     */
    public const STORE_TO_YVES_HOST_MAPPING = 'AVAILABILITY_NOTIFICATION:STORE_TO_YVES_HOST_MAPPING';

    /**
     * Specification:
     * - Defines base URL Yves port.
     *
     * @api
     *
     * @var string
     */
    public const BASE_URL_YVES_PORT = 'AVAILABILITY_NOTIFICATION:BASE_URL_YVES_PORT';
}
