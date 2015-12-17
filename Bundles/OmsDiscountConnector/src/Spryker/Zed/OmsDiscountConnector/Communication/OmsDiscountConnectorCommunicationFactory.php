<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\OmsDiscountConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\OmsDiscountConnector\OmsDiscountConnectorDependencyProvider;

class OmsDiscountConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return DiscountFacade
     */
    public function createDiscountFacade()
    {
        return $this->getProvidedDependency(OmsDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }

}
