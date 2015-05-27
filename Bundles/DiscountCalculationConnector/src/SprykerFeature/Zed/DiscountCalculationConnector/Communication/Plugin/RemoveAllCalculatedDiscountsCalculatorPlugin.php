<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Communication\Plugin;

use Generated\Shared\Calculation\OrderInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use SprykerFeature\Zed\DiscountCalculationConnector\Communication\DiscountCalculationConnectorDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method DiscountCalculationConnectorDependencyContainer getDependencyContainer()
 */
class RemoveAllCalculatedDiscountsCalculatorPlugin extends AbstractPlugin implements
    CalculatorPluginInterface
{

    /**
     * @param OrderInterface $calculableContainer
     */
    public function recalculate(OrderInterface $calculableContainer)
    {
        $this->getDependencyContainer()
            ->getDiscountCalculationFacade()
            ->recalculateRemoveAllCalculatedDiscounts($calculableContainer)
        ;
    }
}
