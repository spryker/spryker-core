<?php

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition;

class AlwaysFalse implements ConditionInterface
{

    public function check(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem)
    {
        return false;
    }

}
