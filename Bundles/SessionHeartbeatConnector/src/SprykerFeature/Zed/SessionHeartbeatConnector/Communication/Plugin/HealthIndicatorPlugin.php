<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SessionHeartbeatConnector\Communication\Plugin;

use Generated\Shared\Heartbeat\HealthIndicatorReportInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;
use SprykerFeature\Zed\SessionHeartbeatConnector\Business\SessionHeartbeatConnectorFacade;

/**
 * @method SessionHeartbeatConnectorFacade getFacade()
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
