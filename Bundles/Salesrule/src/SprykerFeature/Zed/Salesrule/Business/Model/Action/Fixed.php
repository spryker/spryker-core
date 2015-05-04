<?php
namespace SprykerFeature\Zed\Salesrule\Business\Model\Action;

use Generated\Shared\Transfer\SalesOrderItemTransfer;

class Fixed extends AbstractAction
{
    /**
     * @var int
     */
    protected $discountAmount;

    /**
     * @var float
     */
    protected $roundingError = 0.00;

    /**
     * @return int
     */
    public function execute()
    {
        $totalDiscount = 0;
        $itemExpenseDiscount = 0;
        $orderExpenseDiscount = 0;
        $itemOptionDiscount = 0;

        /* @var OrderItem $item */
        foreach ($this->items as $item) {
            $discountAmount = $this->getCalculatedDiscountAmount($item);

            if ($discountAmount > 0) {
                $this->setDiscountAmount($item, $discountAmount);
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

            $totalDiscount += $discountAmount + $itemExpenseDiscount + $itemOptionDiscount;
        }

        /* @var \SprykerFeature\Shared\Calculation\Transfer\Expense $expense */
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
     * @return int
     */
    protected function getCalculatedDiscountAmount($element)
    {
        $calculatedDiscountAmount = $this->calculateDiscountDistribution($element);
        return (int) round($calculatedDiscountAmount >= $element->getGrossPrice() ? 0 : $calculatedDiscountAmount);
    }

    /**
     * @param OrderItem|\SprykerFeature\Shared\Calculation\Transfer\Expense $element
     * @return float
     */
    protected function calculateDiscountDistribution($element)
    {
        $grandTotalWithoutDiscounts = $this->order->getTotals()->getGrandTotal();
        $percentage = $element->getGrossPrice() / $grandTotalWithoutDiscounts;

        $discountAmount = $this->roundingError + $this->loadSalesrule()->getAmount() * $percentage;
        $discountAmountRounded = round($discountAmount, 2);
        $this->roundingError = $discountAmount - $discountAmountRounded;
        $result = $discountAmountRounded * 100;
        return $result;
    }
}
