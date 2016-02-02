<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\CalculationCheckoutConnector\CalculationCheckoutConnectorDependencyProvider;
use Spryker\Zed\CalculationCheckoutConnector\CalculationCheckoutConnectorConfig;

/**
 * @method CalculationCheckoutConnectorConfig getConfig()
 */
class CalculationCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CalculationCheckoutConnector\Dependency\Facade\CalculationCheckoutConnectorToCalculationInterface
     */
    public function getCalculationFacade()
    {
        return $this->getProvidedDependency(CalculationCheckoutConnectorDependencyProvider::FACADE_CALCULATION);
    }

}
