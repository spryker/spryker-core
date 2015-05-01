<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Communication\Plugin;

use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Zed\DiscountCalculationConnector\Communication\DiscountCalculationConnectorDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method DiscountCalculationConnectorDependencyContainer getDependencyContainer()
 */
class RemoveAllCalculatedDiscountsCalculatorPlugin extends AbstractPlugin implements
    CalculatorPluginInterface
{

    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculate(CalculableContainerInterface $calculableContainer)
    {
        $this->getDependencyContainer()
            ->getDiscountCalculationFacade()
            ->recalculateRemoveAllCalculatedDiscounts($calculableContainer)
        ;
    }
}
