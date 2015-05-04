<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\AbstractCalculator;

class RemoveAllCalculatedDiscountsCalculator extends AbstractCalculator
{

    /**
     * @param DiscountableContainerInterface $calculableContainer
     */
    public function recalculate(DiscountableContainerInterface $calculableContainer)
    {
        foreach ($calculableContainer->getItems() as $item) {
            $item->setDiscounts(new \Generated\Shared\Transfer\CalculationDiscountTransfer());

            foreach ($item->getOptions() as $option) {
                $option->setDiscounts(new \Generated\Shared\Transfer\CalculationDiscountTransfer());
            }
            foreach ($item->getExpenses() as $expense) {
                $expense->setDiscounts(new \Generated\Shared\Transfer\CalculationDiscountTransfer());
            }
        }

        foreach ($calculableContainer->getExpenses() as $expense) {
            $expense->setDiscounts(new \Generated\Shared\Transfer\CalculationDiscountTransfer());
        }

        $calculableContainer->setDiscounts(new \Generated\Shared\Transfer\CalculationDiscountTransfer());
    }
}
