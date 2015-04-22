<?php

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition;

class AlwaysTrue implements ConditionInterface
{

    public function check(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem)
    {
        return true;
    }

}
