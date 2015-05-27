<?php

namespace SprykerFeature\Zed\Calculation\Communication\Plugin;

use Generated\Shared\Calculation\OrderInterface;
use SprykerFeature\Zed\Calculation\Communication\CalculationDependencyContainer;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method CalculationDependencyContainer getDependencyContainer()
 */
class ItemPriceToPayCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{

    /**
     * @param OrderInterface $calculableContainer
     */
    public function recalculate(OrderInterface $calculableContainer)
    {
        $this->getDependencyContainer()->getCalculationFacade()->recalculateItemPriceToPay($calculableContainer);
    }
}
