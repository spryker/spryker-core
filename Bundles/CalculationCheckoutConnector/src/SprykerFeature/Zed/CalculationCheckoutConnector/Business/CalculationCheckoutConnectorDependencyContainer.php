<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CalculationCheckoutConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\CalculationCheckoutConnector\CalculationCheckoutConnectorDependencyProvider;
use SprykerFeature\Zed\Calculation\Business\CalculationFacade;

class CalculationCheckoutConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return CalculationFacade
     */
    public function getCalculationFacade()
    {
        return $this->getProvidedDependency(CalculationCheckoutConnectorDependencyProvider::FACADE_CALCULATION);
    }

}
