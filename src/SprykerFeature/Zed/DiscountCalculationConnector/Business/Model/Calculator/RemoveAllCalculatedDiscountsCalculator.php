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
            $item->setDiscounts($this->locator->calculation()->transferDiscountCollection());

            foreach ($item->getOptions() as $option) {
                $option->setDiscounts($this->locator->calculation()->transferDiscountCollection());
            }
            foreach ($item->getExpenses() as $expense) {
                $expense->setDiscounts($this->locator->calculation()->transferDiscountCollection());
            }
        }

        foreach ($calculableContainer->getExpenses() as $expense) {
            $expense->setDiscounts($this->locator->calculation()->transferDiscountCollection());
        }

        $calculableContainer->setDiscounts($this->locator->calculation()->transferDiscountCollection());
    }
}
