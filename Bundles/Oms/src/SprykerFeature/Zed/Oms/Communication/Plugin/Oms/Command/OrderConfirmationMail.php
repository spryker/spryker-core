<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command;

use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature_Shared_Library_Log;

class OrderConfirmationMail implements CommandByOrderInterface
{

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     *
     * @return array $returnArray
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        SprykerFeature_Shared_Library_Log::log('Command OrderConfirmationMail by Order for nr of items: ' . count($orderItems), 'statemachine.log');
    }

}
