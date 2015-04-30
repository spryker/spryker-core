<?php

namespace SprykerFeature\Zed\PriceCartConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\PriceCartConnector\Business\PriceCartConnectorFacade;

class PriceCartConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return PriceCartConnectorFacade
     */
    public function createFacade()
    {
        return $this->getLocator()->priceCartConnector()->facade();
    }
}
 