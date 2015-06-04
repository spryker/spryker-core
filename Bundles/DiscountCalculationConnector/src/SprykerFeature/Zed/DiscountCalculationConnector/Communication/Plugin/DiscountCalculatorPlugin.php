<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Communication\Plugin;

use Generated\Shared\Calculation\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use SprykerFeature\Zed\DiscountCalculationConnector\Communication\DiscountCalculationConnectorDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method DiscountCalculationConnectorDependencyContainer getDependencyContainer()
 */
class DiscountCalculatorPlugin extends AbstractPlugin implements
    CalculatorPluginInterface
{

    /**
     * @ param OrderInterface $calculableContainer
     * @param CalculableInterface $calculableContainer
     * @return array
     */
    public function recalculate(CalculableInterface $calculableContainer)
    //public function recalculate(OrderInterface $calculableContainer)
    {
        return $this->getDependencyContainer()->getDiscountFacade()->calculateDiscounts($calculableContainer);
    }
}
