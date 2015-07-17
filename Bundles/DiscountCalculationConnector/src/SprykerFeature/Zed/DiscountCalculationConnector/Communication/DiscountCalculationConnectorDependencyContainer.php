<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Communication;

use SprykerFeature\Zed\DiscountCalculationConnector\Business\DiscountCalculationConnectorFacade;
use SprykerFeature\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountFacadeInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;

class DiscountCalculationConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return DiscountFacadeInterface
     */
    public function getDiscountFacade()
    {
        return $this->getLocator()->discount()->facade();
    }

    /**
     * @return DiscountCalculationConnectorFacade
     */
    public function getDiscountCalculationFacade()
    {
        return $this->getLocator()->discountCalculationConnector()->facade();
    }

}
