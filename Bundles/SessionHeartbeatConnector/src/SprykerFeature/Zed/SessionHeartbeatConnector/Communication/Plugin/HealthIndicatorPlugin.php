<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SessionHeartbeatConnector\Communication\Plugin;

use Generated\Shared\Transfer\HealthIndicatorReportTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;
use SprykerFeature\Zed\SessionHeartbeatConnector\Business\SessionHeartbeatConnectorFacade;

/**
 * @method SessionHeartbeatConnectorFacade getFacade()
 */
class HealthIndicatorPlugin extends AbstractPlugin implements HealthIndicatorInterface
{

    /**
     * @return HealthIndicatorReportTransfer
     */
    public function doHealthCheck()
    {
        return $this->getFacade()->doHealthCheck();
    }

}
