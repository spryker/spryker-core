<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacade;

class PriceCartConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return PriceCartConnectorFacade
     */
    public function createFacade()
    {
        return $this->getLocator()->priceCartConnector()->facade();
    }

}
