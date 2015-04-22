<?php

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition;

interface ConditionInterface
{

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     * @return bool
     */
    public function check(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem);

}
