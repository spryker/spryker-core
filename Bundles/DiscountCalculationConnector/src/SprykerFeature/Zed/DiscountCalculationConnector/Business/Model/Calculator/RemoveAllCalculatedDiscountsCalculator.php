<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\DiscountCalculationConnector\OrderInterface;
use Generated\Shared\Transfer\DiscountTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

class RemoveAllCalculatedDiscountsCalculator
{

    /**
     * @ param OrderInterface $calculableContainer
     * @param CalculableInterface $calculableContainer
     */
    public function recalculate(CalculableInterface $calculableContainer)
    //public function recalculate(OrderInterface $calculableContainer)
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
