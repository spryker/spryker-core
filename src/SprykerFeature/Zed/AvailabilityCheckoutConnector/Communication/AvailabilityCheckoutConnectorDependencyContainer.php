<?php

namespace SprykerFeature\Zed\AvailabilityCheckoutConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\AvailabilityCheckoutConnector\Dependency\Facade\AvailabilityToCheckoutConnectorFacadeInterface as AvailabilityFacade;

class AvailabilityCheckoutConnectorDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return AvailabilityFacade
     */
    public function getAvailabilityFacade()
    {
        return $this->getLocator()->availability()->facade();
    }

}
