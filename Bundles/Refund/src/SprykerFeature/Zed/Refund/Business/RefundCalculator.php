<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Business;

use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

/**
 *
 */
class RefundCalculator
{

    /**
     * @param $orderItems
     * @param SpySalesOrder $orderEntity
     *
     * @return int
     */
    public function calculateAmount($orderItems, SpySalesOrder $orderEntity) {
        $amount = 0;

        $refunds = $orderEntity->getSpyRefunds();
        $refundAmount = 0;
        foreach ($refunds as $refund) {
            $refundAmount += $refund->getAmount();
        }
        //TODO: TBD how to check if expenses have to be also refunded (last item?)

        /** @var SpySalesOrderItem $orderItem */
        foreach ($orderItems as $orderItem) {
            $amount += $orderItem->getPriceToPay();
        }
        return $amount;
    }

}
