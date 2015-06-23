<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\CalculationCheckoutConnector\Business;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\CalculationCheckoutConnector\CalculationCheckoutConnectorDependencyProvider;
use SprykerFeature\CalculationCheckoutConnector\Dependency\Facade\CalculationCheckoutConnectorToCalculationInterface;

class CalculationCheckoutConnectorDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return CalculationCheckoutConnectorToCalculationInterface
     */
    public function getCalculationFacade()
    {
        return $this->getProvidedDependency(CalculationCheckoutConnectorDependencyProvider::FACADE_CALCULATION);
    }
}
