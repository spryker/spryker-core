<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Communication\Plugin\Oms\Command;

use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Shared\Library\Log;

class DecreaseStock implements CommandByItemInterface
{

    /**
     * @param SpySalesOrderItem $orderItem
     * @param ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(SpySalesOrderItem $orderItem, ReadOnlyArrayObject $data)
    {
        $message = sprintf('Command DecreaseStock by Item for Item %s (quantity %s)', $orderItem->getIdSalesOrderItem(), $orderItem->getQuantity());
        Log::log($message, 'statemachine.log');

        return [];
    }

}
