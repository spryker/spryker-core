<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PropelHeartbeatConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\PropelHeartbeatConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;

/**
 * @method PropelHeartbeatConnectorBusiness getFactory()
 */
class PropelHeartbeatConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return HealthIndicatorInterface
     */
    public function createHealthIndicator()
    {
        return $this->getFactory()->createAssistantPropelHealthIndicator();
    }

}
