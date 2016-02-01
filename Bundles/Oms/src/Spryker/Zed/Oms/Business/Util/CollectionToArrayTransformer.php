<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Business\Util;

use Propel\Runtime\Collection\ObjectCollection;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

class CollectionToArrayTransformer implements CollectionToArrayTransformerInterface
{

    /**
     * @deprecated
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     *
     * @return SpySalesOrderItem[] $orderItems
     */
    public function transformCollectionToArray(ObjectCollection $orderItems)
    {
        trigger_error('Deprecated, will be removed.', E_USER_DEPRECATED);

        $orderItemsArray = [];
        foreach ($orderItems as $orderItem) {
            $orderItemsArray[] = $orderItem;
        }

        return $orderItemsArray;
    }

}
