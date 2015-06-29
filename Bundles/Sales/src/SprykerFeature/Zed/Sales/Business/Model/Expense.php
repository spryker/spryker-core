<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

use SprykerFeature\Shared\Sales\Code\ExpenseConstants;

class SprykerFeature_Zed_Sales_Business_Model_Expense
{

    /**
     * @deprecated
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return int
     */
    public function getShippingCostsForOrder(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        $shippingCosts = 0;

        /** @var $expense \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesExpense */
        foreach ($order->getExpenses() as $expense) {
            if ($expense->getType() === ExpenseConstants::EXPENSE_SHIPPING) {
                $shippingCosts += $expense->getValue();
                break;
            }
        }

        $orderItems = $this->factory->createModelOrderprocessFinder()->getOrderItemsForGrandTotalAggreate($order);

        /** @var $item \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem */
        foreach ($orderItems as $item) {
            foreach ($item->getExpenses() as $expense) {
                if ($expense->getType() === ExpenseConstants::EXPENSE_SHIPPING) {
                    $shippingCosts += $expense->getValue();
                    break;
                }
            }
        }

        return $shippingCosts;
    }
}
