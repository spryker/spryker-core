<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Communication\Plugin;

use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Generated\Shared\Transfer\Calculation\DependencyCalculableContainerInterfaceTransfer;
use SprykerFeature\Zed\DiscountCalculationConnector\Communication\DiscountCalculationConnectorDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method DiscountCalculationConnectorDependencyContainer getDependencyContainer()
 */
class DiscountCalculatorPlugin extends AbstractPlugin implements
    CalculatorPluginInterface
{

    /**
     * @param CalculableContainerInterface $calculableContainer
     * @return array
     */
    public function recalculate(CalculableContainerInterface $calculableContainer)
    {
        return $this->getDependencyContainer()->getDiscountFacade()->calculateDiscounts($calculableContainer);
    }
}
