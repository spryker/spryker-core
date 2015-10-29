<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface ConditionInterface
{

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem);

}
