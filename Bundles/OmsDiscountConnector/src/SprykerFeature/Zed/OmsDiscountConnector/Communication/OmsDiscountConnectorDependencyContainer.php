<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\OmsDiscountConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\OmsDiscountConnector\OmsDiscountConnectorDependencyProvider;

class OmsDiscountConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{
    /**
     * @return DiscountFacade
     */
    public function createDiscountFacade()
    {
        return $this->getProvidedDependency(OmsDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }
}
