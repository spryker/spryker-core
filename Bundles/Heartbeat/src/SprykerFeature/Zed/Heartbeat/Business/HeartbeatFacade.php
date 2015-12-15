<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Heartbeat\Business;

use Generated\Shared\Transfer\HealthReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method HeartbeatDependencyContainer getDependencyContainer()
 */
class HeartbeatFacade extends AbstractFacade
{

    /**
     * @return bool
     */
    public function isSystemAlive()
    {
        return $this->getDependencyContainer()->createDoctor()->doHealthCheck()->isPatientAlive();
    }

    /**
     * @return HealthReportTransfer
     */
    public function getReport()
    {
        return $this->getDependencyContainer()->createDoctor()->doHealthCheck()->getReport();
    }

    /**
     * @return HealthIndicatorReportTransfer
     */
    public function doPropelHealthCheck()
    {
        return $this->getDependencyContainer()->createPropelHealthIndicator()->doHealthCheck();
    }

    /**
     * @return HealthIndicatorReportTransfer
     */
    public function doSearchHealthCheck()
    {
        return $this->getDependencyContainer()->createSearchHealthIndicator()->doHealthCheck();
    }

    /**
     * @return HealthIndicatorReportTransfer
     */
    public function doSessionHealthCheck()
    {
        return $this->getDependencyContainer()->createSessionHealthIndicator()->doHealthCheck();
    }

    /**
     * @return HealthIndicatorReportTransfer
     */
    public function doStorageHealthCheck()
    {
        return $this->getDependencyContainer()->createStorageHealthIndicator()->doHealthCheck();
    }

}
