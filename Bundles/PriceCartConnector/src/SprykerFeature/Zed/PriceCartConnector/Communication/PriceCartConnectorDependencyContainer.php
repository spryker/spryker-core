<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PriceCartConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\PriceCartConnector\Business\PriceCartConnectorFacade;

class PriceCartConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return PriceCartConnectorFacade
     */
    public function createFacade()
    {
        return $this->getLocator()->priceCartConnector()->facade();
    }

}
