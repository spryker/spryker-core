<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Communication;

use Spryker\Zed\DiscountCalculationConnector\Business\DiscountCalculationConnectorFacade;
use Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToDiscountInterface;
use Spryker\Zed\DiscountCalculationConnector\DiscountCalculationConnectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\DiscountCalculationConnector\DiscountCalculationConnectorConfig;

/**
 * @method DiscountCalculationConnectorConfig getConfig()
 */
class DiscountCalculationConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return DiscountCalculationToDiscountInterface
     */
    public function getDiscountFacade()
    {
        return $this->getProvidedDependency(DiscountCalculationConnectorDependencyProvider::FACADE_DISCOUNT);
    }

}
