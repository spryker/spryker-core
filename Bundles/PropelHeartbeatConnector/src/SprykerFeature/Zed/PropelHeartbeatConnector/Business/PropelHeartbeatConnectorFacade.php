<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PropelHeartbeatConnector\Business;

use Generated\Shared\Heartbeat\HealthIndicatorReportInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;

/**
 * @method PropelHeartbeatConnectorDependencyContainer getDependencyContainer()
 */
class PropelHeartbeatConnectorFacade extends AbstractFacade implements HealthIndicatorInterface
{

    /**
     * @return HealthIndicatorReportInterface
     */
    public function doHealthCheck()
    {
        return $this->getDependencyContainer()->createHealthIndicator()->doHealthCheck();
    }

}
