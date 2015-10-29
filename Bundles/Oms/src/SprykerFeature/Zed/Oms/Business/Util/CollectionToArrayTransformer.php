<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Util;

use Propel\Runtime\Collection\ObjectCollection;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

class CollectionToArrayTransformer implements CollectionToArrayTransformerInterface
{

    /**
     * @deprecated
     *
     * @param ObjectCollection $orderItems
     *
     * @return SpySalesOrderItem[] $orderItems
     */
    public function transformCollectionToArray(ObjectCollection $orderItems)
    {
        $orderItemsArray = [];
        foreach ($orderItems as $orderItem) {
            $orderItemsArray[] = $orderItem;
        }

        return $orderItemsArray;
    }

}
