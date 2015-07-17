<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command;

use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

interface CommandByItemInterface extends CommandInterface
{

    /**
     * @param SpySalesOrderItem $orderItem
     * @param ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(SpySalesOrderItem $orderItem, ReadOnlyArrayObject $data);

}
