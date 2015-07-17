<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition;

use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

class AlwaysFalse extends AbstractCondition
{

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        return false;
    }

}
