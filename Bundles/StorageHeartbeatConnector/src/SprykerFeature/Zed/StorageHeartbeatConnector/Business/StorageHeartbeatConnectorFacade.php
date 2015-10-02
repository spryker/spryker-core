<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\StorageHeartbeatConnector\Business;

use Generated\Shared\Heartbeat\HealthIndicatorReportInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;

/**
 * @method StorageHeartbeatConnectorDependencyContainer getDependencyContainer()
 */
class StorageHeartbeatConnectorFacade extends AbstractFacade implements HealthIndicatorInterface
{

    /**
     * @return HealthIndicatorReportInterface
     */
    public function doHealthCheck()
    {
        return $this->getDependencyContainer()->createHealthIndicator()->doHealthCheck();
    }

}
