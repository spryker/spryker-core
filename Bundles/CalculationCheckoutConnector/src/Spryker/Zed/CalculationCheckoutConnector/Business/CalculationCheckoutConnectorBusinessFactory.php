<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Business;

use Spryker\Zed\CalculationCheckoutConnector\Business\Model\CartAmountPrecondition;
use Spryker\Zed\CalculationCheckoutConnector\Dependency\Facade\CalculationCheckoutConnectorToCalculationInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\CalculationCheckoutConnector\CalculationCheckoutConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\CalculationCheckoutConnector\CalculationCheckoutConnectorConfig getConfig()
 */
class CalculationCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return CalculationCheckoutConnectorToCalculationInterface
     */
    public function getCalculationFacade()
    {
        return $this->getProvidedDependency(CalculationCheckoutConnectorDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @return CartAmountPrecondition
     */
    public function createCartAmountPrecondition()
    {
        return new CartAmountPrecondition($this->getCalculationFacade());
    }

}
