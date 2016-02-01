<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Heartbeat\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;
use Spryker\Zed\Heartbeat\Business\HeartbeatFacade;
use Spryker\Zed\Heartbeat\Communication\HeartbeatCommunicationFactory;

/**
 * @method HeartbeatFacade getFacade()
 * @method HeartbeatCommunicationFactory getFactory()
 */
class PropelHealthIndicatorPlugin extends AbstractPlugin implements HealthIndicatorInterface
{

    /**
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doHealthCheck()
    {
        return $this->getFacade()->doPropelHealthCheck();
    }

}
