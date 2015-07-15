<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\CalculationCheckoutConnector\Business;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\CalculationCheckoutConnector\CalculationCheckoutConnectorDependencyProvider;
use SprykerFeature\CalculationCheckoutConnector\Dependency\Facade\CalculationCheckoutConnectorToCalculationInterface;

class CalculationCheckoutConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return CalculationCheckoutConnectorToCalculationInterface
     */
    public function getCalculationFacade()
    {
        return $this->getProvidedDependency(CalculationCheckoutConnectorDependencyProvider::FACADE_CALCULATION);
    }

}
