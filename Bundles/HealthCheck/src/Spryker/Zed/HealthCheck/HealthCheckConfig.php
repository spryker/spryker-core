<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\HealthCheck;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\HealthCheck\HealthCheckConfig getSharedConfig()
 */
class HealthCheckConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isHealthCheckEnabled(): bool
    {
        return $this->getSharedConfig()->isHealthCheckEnabled();
    }
}
