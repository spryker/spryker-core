<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Communication\Plugin;

use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

interface RefundCalculationPluginInterface
{

    /**
     * @return int
     */
    public function calculateAmount($orderItems, SpySalesOrderItem $orderEntity);
}
