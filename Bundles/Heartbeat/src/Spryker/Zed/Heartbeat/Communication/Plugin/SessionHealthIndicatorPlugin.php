<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Heartbeat\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;

/**
 * @method \Spryker\Zed\Heartbeat\Business\HeartbeatFacade getFacade()
 * @method \Spryker\Zed\Heartbeat\Communication\HeartbeatCommunicationFactory getFactory()
 */
class SessionHealthIndicatorPlugin extends AbstractPlugin implements HealthIndicatorInterface
{

    /**
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doHealthCheck()
    {
        return $this->getFacade()->doSessionHealthCheck();
    }

}
