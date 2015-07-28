<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\Split;

use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

class Calculator implements CalculatorInterface
{
    /**
     * @param SpySalesOrderItem $salesOrderItem
     * @param integer $quantity
     *
     * @return integer
     */
    public function calculateQuantityAmountLeft(SpySalesOrderItem $salesOrderItem, $quantity)
    {
        return $salesOrderItem->getQuantity() - $quantity;
    }

}
