<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\StorageHeartbeatConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;
use SprykerFeature\Zed\StorageHeartbeatConnector\Business\StorageHeartbeatConnectorFacade;

/**
 * @method StorageHeartbeatConnectorFacade getFacade()
 */
class HealthIndicatorPlugin extends AbstractPlugin implements HealthIndicatorInterface
{

    /**
     * @return HealthIndicatorReportInterface
     */
    public function doHealthCheck()
    {
        return $this->getFacade()->doHealthCheck();
    }

}
