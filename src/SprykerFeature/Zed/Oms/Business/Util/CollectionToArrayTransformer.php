<?php

namespace SprykerFeature\Zed\Oms\Business\Util;

use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

class CollectionToArrayTransformer implements CollectionToArrayTransformerInterface
{
    /**
     * @param ObjectCollection $orderItems
     * @return SpySalesOrderItem[] $orderItems
     */
    public function transformCollectionToArray(ObjectCollection $orderItems)
    {
        $orderItemsArray = array();
        foreach ($orderItems as $orderItem) {
            $orderItemsArray[] = $orderItem;
        }

        return $orderItemsArray;
    }
}
