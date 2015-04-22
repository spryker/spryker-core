<?php

namespace SprykerFeature\Zed\PriceCartConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\AvailabilityCartConnector\Dependency\Facade\PriceToPriceCartConnectorFacadeInterface;

class PriceCartConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return PriceToPriceCartConnectorFacadeInterface
     */
    public function getPriceFacade()
    {
        return $this->getLocator()->price()->facade();
    }

}
