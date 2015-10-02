<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchHeartbeatConnector\Communication\Plugin;

use Generated\Shared\Heartbeat\HealthIndicatorReportInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;
use SprykerFeature\Zed\SearchHeartbeatConnector\Business\SearchHeartbeatConnectorFacade;

/**
 * @method SearchHeartbeatConnectorFacade getFacade()
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
