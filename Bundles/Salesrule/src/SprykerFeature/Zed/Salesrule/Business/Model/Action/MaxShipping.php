<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model\Action;

use Generated\Shared\Transfer\CalculationExpenseTransfer;
use SprykerFeature\Shared\Sales\Code\ExpenseConstants;

class MaxShipping extends AbstractAction
{

    /**
     * @return bool
     */
    public function execute()
    {
        return $this->reduceShippingCosts($this->order->getExpenses());
    }

    /**
     * @param CalculationExpenseTransfer $expenses
     * @return bool
     */
    protected function reduceShippingCosts(CalculationExpenseTransfer $expenses)
    {
        foreach ($expenses as $expense) {
            /* @var $expense \SprykerFeature\Shared\Calculation\Transfer\Expense */
            if ($expense->getType() == ExpenseConstants::EXPENSE_SHIPPING) {

                if ($expense->getGrossPrice() < $this->loadSalesrule()->getAmount()) {
                    $discountAmount = $expense->getGrossPrice();
                } else {
                    $discountAmount = $this->loadSalesrule()->getAmount();
                }

                $expense->addDiscount($this->getDiscount($discountAmount));
                return true;
            }
        }
        return false;
    }
}
