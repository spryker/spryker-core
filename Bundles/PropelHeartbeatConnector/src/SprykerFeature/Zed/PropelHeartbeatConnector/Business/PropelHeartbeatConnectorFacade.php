<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PropelHeartbeatConnector\Business;

use Generated\Shared\Transfer\HealthIndicatorReportTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;

/**
 * @method PropelHeartbeatConnectorDependencyContainer getDependencyContainer()
 */
class PropelHeartbeatConnectorFacade extends AbstractFacade implements HealthIndicatorInterface
{

    /**
     * @return HealthIndicatorReportTransfer
     */
    public function doHealthCheck()
    {
        return $this->getDependencyContainer()->createHealthIndicator()->doHealthCheck();
    }

}
