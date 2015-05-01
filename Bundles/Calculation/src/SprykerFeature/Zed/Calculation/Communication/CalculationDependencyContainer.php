<?php

namespace SprykerFeature\Zed\Calculation\Communication;

use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use SprykerFeature\Zed\Calculation\Business\Model\StackExecutor;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;

class CalculationDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return CalculationFacade
     */
    public function getCalculationFacade()
    {
        return $this->getLocator()->calculation()->facade();
    }
}
