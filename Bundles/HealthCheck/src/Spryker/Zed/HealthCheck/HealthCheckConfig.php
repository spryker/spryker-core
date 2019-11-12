<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\HealthCheck;

use Spryker\Shared\HealthCheckConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

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
