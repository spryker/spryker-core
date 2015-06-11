<?php

namespace SprykerFeature\Zed\Checkout\Communication;

use Pyz\Zed\Calculation\Business\CalculationFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Checkout\Business\CheckoutFacade;
use SprykerFeature\Zed\Checkout\CheckoutDependencyProvider;

class CheckoutDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return CalculationFacade
     *
     * @throws \ErrorException
     */
    public function createCalculationFacade()
    {
        return $this->getInjectedDependency(CheckoutDependencyProvider::FACADE_CALCULATION);
    }

}
