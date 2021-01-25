<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class HealthCheckConfig extends AbstractSharedConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isHealthCheckEnabled(): bool
    {
        return $this->get(HealthCheckConstants::HEALTH_CHECK_ENABLED, false);
    }
}
