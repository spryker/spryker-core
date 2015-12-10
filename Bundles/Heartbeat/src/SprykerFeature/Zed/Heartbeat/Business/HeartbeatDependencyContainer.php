<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Heartbeat\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Heartbeat\Business\Ambulance\Doctor;
use SprykerFeature\Zed\Heartbeat\HeartbeatConfig;

/**
 * @method HeartbeatConfig getConfig()
 */
class HeartbeatDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Doctor
     */
    public function createDoctor()
    {
        return new Doctor(
            $this->getConfig()->getHealthIndicator()
        );
    }

}
