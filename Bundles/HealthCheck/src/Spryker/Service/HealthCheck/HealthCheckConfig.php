<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck;

use Spryker\Service\Kernel\AbstractBundleConfig;
use Spryker\Shared\HealthCheck\HealthCheckConstants;

/**
 * @method \Spryker\Shared\HealthCheck\HealthCheckConfig getSharedConfig()
 */
class HealthCheckConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isHealthCheckEnabled(): bool
    {
        return $this->get(HealthCheckConstants::HEALTH_CHECK_ENABLED, true);
    }

    /**
     * @return int
     */
    public function getSuccessHealthCheckStatus(): int
    {
        return $this->getSharedConfig()->getSuccessHealthCheckStatus();
    }

    /**
     * @return int
     */
    public function getForbiddenHealthCheckStatus(): int
    {
        return $this->getSharedConfig()->getForbiddenHealthCheckStatus();
    }

    /**
     * @return int
     */
    public function getUnavailableHealthCheckStatus(): int
    {
        return $this->getSharedConfig()->getUnavailableHealthCheckStatus();
    }
}
