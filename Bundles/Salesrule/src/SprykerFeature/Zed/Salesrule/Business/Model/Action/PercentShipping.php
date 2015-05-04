<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model\Action;

use SprykerFeature\Shared\Sales\Code\ExpenseConstants;
use Generated\Shared\Transfer\CalculationExpenseTransfer;

class PercentShipping extends AbstractAction
{

    /**
     * @return bool
     */
    public function execute()
    {
        return $this->reduceShippingCosts($this->order->getExpenses());
    }

    /**
     * @param \SprykerFeature\Shared\Calculation\Transfer\ExpenseCollection $expenses
     * @return bool
     */
    protected function reduceShippingCosts(CalculationExpenseTransfer $expenses)
    {
        foreach ($expenses as $expense) {
            /* @var Expense $expense */
            if ($expense->getType() == ExpenseConstants::EXPENSE_SHIPPING) {
                $percentage = $this->loadSalesrule()->getAmount();

                if ($this->loadSalesrule()->getAmount() > 100) {
                    $percentage = 100;
                }

                if ($this->loadSalesrule()->getAmount() < 0) {
                    return false;
                }

                $discountAmount = round($expense->getGrossPrice() * $percentage / 100);
                $expense->addDiscount($this->getDiscount($discountAmount));
                return true;
            }
        }
        return false;
    }
}
