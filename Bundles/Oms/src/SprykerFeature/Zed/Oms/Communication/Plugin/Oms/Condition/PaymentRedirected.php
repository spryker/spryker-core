<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerFeature\Shared\Library\Log;

class PaymentRedirected extends AbstractCondition
{

    /**
     * @param SpySalesOrderItem $orderItem
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
