<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Heartbeat\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method HeartbeatDependencyContainer getDependencyContainer()
 */
class HeartbeatFacade extends AbstractFacade
{

    /**
     * @return bool
     */
    public function check()
    {
        return $this->getDependencyContainer()->createHeartbeatChecker()->check();
    }

}
