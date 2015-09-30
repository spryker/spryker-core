<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Heartbeat;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\Heartbeat\Business\Check\HealthIndicatorInterface;

class HeartbeatConfig extends AbstractBundleConfig
{

    /**
     * @return HealthIndicatorInterface[]
     */
    public function getHeartbeatChecker()
    {
        return [];
    }

}
