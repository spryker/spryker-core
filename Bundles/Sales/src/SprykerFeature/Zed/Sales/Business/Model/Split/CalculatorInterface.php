<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Sales\Business\Model\Split;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface CalculatorInterface
{

    /**
     * @param SpySalesOrderItem $salesOrderItem
     * @param int $quantity
     *
     * @return int
     */
    public function calculateQuantityAmountLeft(SpySalesOrderItem $salesOrderItem, $quantity);

}
