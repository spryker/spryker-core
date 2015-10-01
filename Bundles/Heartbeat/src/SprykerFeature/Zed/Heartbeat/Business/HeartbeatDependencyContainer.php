<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Heartbeat\Business;

use Generated\Shared\Transfer\HealthReportTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\HeartbeatBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Heartbeat\Business\Ambulance\Doctor;
use SprykerFeature\Zed\Heartbeat\HeartbeatConfig;

/**
 * @method HeartbeatBusiness getFactory()
 * @method HeartbeatConfig getConfig()
 */
class HeartbeatDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Doctor
     */
    public function createDoctor()
    {
        return $this->getFactory()->createAmbulanceDoctor(
            new HealthReportTransfer(),
            $this->getConfig()->getHealthIndicator()
        );
    }

}
