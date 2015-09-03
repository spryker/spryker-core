<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

/**
 * @method  getFacade()
 */
class RefundCalculationPlugin extends AbstractPlugin implements RefundCalculationPluginInterface
{

    /**
     * @param $orderItems
     * @param SpySalesOrderItem $orderEntity
     * @return int
     */
    public function calculateAmount($orderItems, SpySalesOrderItem $orderEntity) {

        return;

        $amount = 0;
        /** @var SpySalesOrderItem $orderItem */
        foreach ($orderItems as $orderItem) {
            $amount += $orderItem->getPriceToPay();
        }
        return $amount;
    }

}
