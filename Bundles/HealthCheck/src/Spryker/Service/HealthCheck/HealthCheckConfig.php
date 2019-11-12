<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck;

use Spryker\Service\Kernel\AbstractBundleConfig;
use Spryker\Shared\HealthCheck\HealthCheckConstants;

class HealthCheckConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isHealthCheckEnabled(): bool
    {
        return $this->get(HealthCheckConstants::HEALTH_CHECK_ENABLED, true);
    }
}
