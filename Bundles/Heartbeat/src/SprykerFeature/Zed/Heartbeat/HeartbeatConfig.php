<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Heartbeat;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;

class HeartbeatConfig extends AbstractBundleConfig
{

    /**
     * @return HealthIndicatorInterface[]
     */
    public function getHealthIndicator()
    {
        return [];
    }

}
