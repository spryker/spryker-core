<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface HealthCheckConstants
{
    /**
     * Specification:
     * - Defines if health check is enabled.
     *
     * @api
     */
    public const HEALTH_CHECK_ENABLED = 'HEALTH_CHECK:HEALTH_CHECK_ENABLED';
}
