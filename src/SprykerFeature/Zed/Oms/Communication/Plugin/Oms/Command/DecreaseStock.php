<?php

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command;

use SprykerFeature\Zed\Oms\Business\Model\Util\ReadOnlyArrayObject;

class DecreaseStock implements CommandByItemInterface
{

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     * @param $data
     * @return void
     */
    public function run(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem, ReadOnlyArrayObject $data)
    {
        \SprykerFeature_Shared_Library_Log::log('Command DecreaseStock by Item', 'statemachine.log');
    }

}
