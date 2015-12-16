<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Heartbeat\Business;

use Generated\Shared\Transfer\HealthReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method HeartbeatDependencyContainer getBusinessFactory()
 */
class HeartbeatFacade extends AbstractFacade
{

    /**
     * @return bool
     */
    public function isSystemAlive()
    {
        return $this->getBusinessFactory()->createDoctor()->doHealthCheck()->isPatientAlive();
    }

    /**
     * @return HealthReportTransfer
     */
    public function getReport()
    {
        return $this->getBusinessFactory()->createDoctor()->doHealthCheck()->getReport();
    }

    /**
     * @return HealthIndicatorReportTransfer
     */
    public function doPropelHealthCheck()
    {
        return $this->getBusinessFactory()->createPropelHealthIndicator()->doHealthCheck();
    }

    /**
     * @return HealthIndicatorReportTransfer
     */
    public function doSearchHealthCheck()
    {
        return $this->getBusinessFactory()->createSearchHealthIndicator()->doHealthCheck();
    }

    /**
     * @return HealthIndicatorReportTransfer
     */
    public function doSessionHealthCheck()
    {
        return $this->getBusinessFactory()->createSessionHealthIndicator()->doHealthCheck();
    }

    /**
     * @return HealthIndicatorReportTransfer
     */
    public function doStorageHealthCheck()
    {
        return $this->getBusinessFactory()->createStorageHealthIndicator()->doHealthCheck();
    }

}
