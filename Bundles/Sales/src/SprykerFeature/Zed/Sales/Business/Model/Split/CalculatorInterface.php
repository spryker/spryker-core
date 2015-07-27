<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Sales\Business\Model\Split;

use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

interface CalculatorInterface
{
    /**
     * @param SpySalesOrderItem $salesOrderItem
     * @param integer           $quantity
     *
     * @return integer
     */
    public function calculateQuantityAmountLeft(SpySalesOrderItem $salesOrderItem, $quantity);
}
