<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchHeartbeatConnector\Business;

use Generated\Shared\Heartbeat\HealthIndicatorReportInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;

/**
 * @method SearchHeartbeatConnectorDependencyContainer getDependencyContainer()
 */
class SearchHeartbeatConnectorFacade extends AbstractFacade implements HealthIndicatorInterface
{

    /**
     * @return HealthIndicatorReportInterface
     */
    public function doHealthCheck()
    {
        return $this->getDependencyContainer()->createHealthIndicator()->doHealthCheck();
    }

}
