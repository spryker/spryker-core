<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Communication;

use Spryker\Zed\DiscountCalculationConnector\DiscountCalculationConnectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\DiscountCalculationConnector\DiscountCalculationConnectorConfig getConfig()
 */
class DiscountCalculationConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToDiscountInterface
     */
    public function getDiscountFacade()
    {
        return $this->getProvidedDependency(DiscountCalculationConnectorDependencyProvider::FACADE_DISCOUNT);
    }

}
