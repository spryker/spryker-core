<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heartbeat\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Heartbeat\Business\HeartbeatBusinessFactory getFactory()
 */
class HeartbeatFacade extends AbstractFacade implements HeartbeatFacadeInterface
{
    /**
     * @api
     *
     * @return bool
     */
    public function isSystemAlive()
    {
        return $this->getFactory()->createDoctor()->doHealthCheck()->isPatientAlive();
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthReportTransfer
     */
    public function getReport()
    {
        return $this->getFactory()->createDoctor()->doHealthCheck()->getReport();
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doPropelHealthCheck()
    {
        return $this->getFactory()->createPropelHealthIndicator()->doHealthCheck();
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doSearchHealthCheck()
    {
        return $this->getFactory()->createSearchHealthIndicator()->doHealthCheck();
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doSessionHealthCheck()
    {
        return $this->getFactory()->createSessionHealthIndicator()->doHealthCheck();
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthIndicatorReportTransfer
     */
    public function doStorageHealthCheck()
    {
        return $this->getFactory()->createStorageHealthIndicator()->doHealthCheck();
    }
}
