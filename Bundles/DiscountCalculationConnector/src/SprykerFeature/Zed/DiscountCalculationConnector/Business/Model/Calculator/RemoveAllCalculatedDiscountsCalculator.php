<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;

class RemoveAllCalculatedDiscountsCalculator
{

    /**
     * @param DiscountableContainerInterface $calculableContainer
     */
    public function recalculate(DiscountableContainerInterface $calculableContainer)
    {
        foreach ($calculableContainer->getItems() as $item) {
            $item->setDiscounts(new \ArrayObject());

            foreach ($item->getOptions() as $option) {
                $option->setDiscounts(new \ArrayObject());
            }
            foreach ($item->getExpenses() as $expense) {
                $expense->setDiscounts(new \ArrayObject);
            }
        }

        foreach ($calculableContainer->getExpenses() as $expense) {
            $expense->setDiscounts(new \ArrayObject());
        }

        $calculableContainer->setDiscounts(new \ArrayObject());
    }
}
