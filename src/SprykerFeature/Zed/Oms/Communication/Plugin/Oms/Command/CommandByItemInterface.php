<?php

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command;

use SprykerFeature\Zed\Oms\Business\Model\Util\ReadOnlyArrayObject;

interface CommandByItemInterface extends CommandInterface
{

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     * @param ReadOnlyArrayObject $data
     * @return void
     */
    public function run(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem, ReadOnlyArrayObject $data);
}
