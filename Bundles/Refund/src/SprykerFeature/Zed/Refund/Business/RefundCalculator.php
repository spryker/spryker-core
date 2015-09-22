<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Business;

use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

class RefundCalculator
{

    /**
     * @param $orderItems
     * @param SpySalesOrder $orderEntity
     *
     * @return int
     */
    public function calculateAmount($orderItems, SpySalesOrder $orderEntity)
    {
        $allRefunds = $orderEntity->getSpyRefunds();

        $idRefund = null;
        /** @var SpySalesOrderItem $orderItem */
        foreach ($orderItems as $orderItem) {
            $idRefund = $orderItem->getFkRefund();
            break;
        }

        $refundAmount = 0;
        foreach ($allRefunds as $refund) {
            if ($refund->getIdRefund() !== $idRefund) {
                continue;
            }
            $refundAmount = $refund->getAmount();
            break;
        }

        return $refundAmount;
    }

}
