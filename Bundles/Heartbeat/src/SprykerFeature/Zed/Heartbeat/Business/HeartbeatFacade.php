<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Heartbeat\Business;

use Generated\Shared\Heartbeat\HealthReportInterface;
use Generated\Shared\Transfer\HealthReportTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

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

}
