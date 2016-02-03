<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Communication\Plugin\Oms\Command;

use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Shared\Library\Log;

class SendOrderConfirmation implements CommandByOrderInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $message = sprintf('Command SendOrderConfirmation by Order for Order %s (%s items)', $orderEntity->getIdSalesOrder(), count($orderItems));
        Log::log($message, 'statemachine.log');

        return [];
    }

}
