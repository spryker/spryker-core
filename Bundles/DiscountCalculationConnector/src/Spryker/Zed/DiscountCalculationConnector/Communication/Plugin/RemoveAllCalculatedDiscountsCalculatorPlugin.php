<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Communication\Plugin;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Spryker\Zed\DiscountCalculationConnector\Communication\DiscountCalculationConnectorCommunicationFactory;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method DiscountCalculationConnectorCommunicationFactory getFactory()
 */
class RemoveAllCalculatedDiscountsCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        $this->getFactory()
            ->getDiscountCalculationFacade()
            ->recalculateRemoveAllCalculatedDiscounts($calculableContainer);
    }

}
