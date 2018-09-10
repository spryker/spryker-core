<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heartbeat\Communication\Plugin;

use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Heartbeat\Business\HeartbeatFacadeInterface getFacade()
 * @method \Spryker\Zed\Heartbeat\Communication\HeartbeatCommunicationFactory getFactory()
 */
class SessionHealthIndicatorPlugin extends AbstractPlugin implements HealthIndicatorInterface
{
    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doHealthCheck()
    {
        return $this->getFacade()->doSessionHealthCheck();
    }
}
