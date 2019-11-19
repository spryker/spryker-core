<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class HealthCheckConfig extends AbstractSharedConfig
{
    protected const HEALTH_CHECK_SUCCESS_STATUS = 200;
    protected const HEALTH_CHECK_FORBIDDEN_STATUS = 403;
    protected const HEALTH_CHECK_UNAVAILABLE_STATUS = 500;

    /**
     * @return int
     */
    public function getSuccessHealthCheckStatus(): int
    {
        return static::HEALTH_CHECK_SUCCESS_STATUS;
    }

    /**
     * @return int
     */
    public function getForbiddenHealthCheckStatus(): int
    {
        return static::HEALTH_CHECK_FORBIDDEN_STATUS;
    }

    /**
     * @return int
     */
    public function getUnavailableHealthCheckStatus(): int
    {
        return static::HEALTH_CHECK_UNAVAILABLE_STATUS;
    }
}
