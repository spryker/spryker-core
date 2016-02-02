<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Heartbeat;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class HeartbeatConfig extends AbstractBundleConfig
{

    /**
     * @return \Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface[]
     */
    public function getHealthIndicator()
    {
        return [];
    }

}
