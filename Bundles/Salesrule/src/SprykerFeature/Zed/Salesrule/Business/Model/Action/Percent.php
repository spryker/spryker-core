<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model\Action;

use SprykerFeature\Shared\Sales\Transfer\OrderItem;

class Percent extends AbstractAction
{
    /**
     * @return int
     */
    public function execute()
    {
        $totalDiscount = 0;
        $itemExpenseDiscount = 0;
        $orderExpenseDiscount = 0;
        $itemOptionDiscount = 0;

        foreach ($this->items as $item) {
            /* @var OrderItem $item */
            $itemDiscount = $this->getCalculatedDiscountAmount($item);

            if ($itemDiscount > 0) {
                $this->setDiscountAmount($item, $itemDiscount);
            }

            // ITEM EXPENSES
            foreach ($item->getExpenses() as $expense) {
                $itemExpenseDiscount = $this->getCalculatedDiscountAmount($expense);

                if ($itemExpenseDiscount > 0) {
                    $this->setDiscountAmount($expense, $itemExpenseDiscount);
                }
            }

            // ITEM OPTIONS
            foreach ($item->getOptions() as $option) {
                $itemOptionDiscount = $this->getCalculatedDiscountAmount($option);

                if ($itemOptionDiscount > 0) {
                    $this->setDiscountAmount($option, $itemOptionDiscount);
                }
            }

            $totalDiscount += $itemDiscount + $itemExpenseDiscount + $itemOptionDiscount;
        }

        // ORDER EXPENSES
        foreach ($this->order->getExpenses() as $expense) {
            $orderExpenseDiscount = $this->getCalculatedDiscountAmount($expense);

            if ($orderExpenseDiscount > 0) {
                $this->setDiscountAmount($expense, $orderExpenseDiscount);
            }
        }

        return $totalDiscount + $orderExpenseDiscount;
    }

    /**
     * @param OrderItem|\SprykerFeature\Shared\Calculation\Transfer\Expense $element
     * @return float
     */
    protected function getCalculatedDiscountAmount($element)
    {
        $calculatedDiscountAmount = $element->getGrossPrice() * $this->loadSalesrule()->getAmount() / 100;
        return round($calculatedDiscountAmount >= $element->getGrossPrice() ? 0 : $calculatedDiscountAmount);
    }
}
