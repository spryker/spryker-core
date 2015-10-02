<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\System\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Heartbeat\Business\HeartbeatFacade;
use SprykerFeature\Zed\System\SystemDependencyProvider;

class SystemDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return HeartbeatFacade
     */
    public function createHeartbeatFacade()
    {
        return $this->getProvidedDependency(SystemDependencyProvider::FACADE_HEARTBEAT);
    }

}
