<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Shared\Library\Log;

class PaymentRedirected extends AbstractCondition
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $b = (microtime() * 1000000 % 2) ? true : false;
        $bS = $b ? 'true' : 'false';
        Log::log('Condition PaymentRedirected for item: ' . $orderItem->getIdSalesOrderItem() . ' ' . $bS, 'statemachine.log');

        return $b;
    }

}
