<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Heartbeat\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Heartbeat\Business\Check\Doctor;
use SprykerFeature\Zed\Heartbeat\HeartbeatConfig;

/**
 * @method HeartbeatBusiness getFactory()
 * @method HeartbeatConfig getConfig()
 */
class HeartbeatDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Doctor
     */
    public function createHeartbeatChecker()
    {
        return $this->getFactory()->createCheckHeartbeat(
            $this->getConfig()->getHeartbeatChecker()
        );
    }

}
