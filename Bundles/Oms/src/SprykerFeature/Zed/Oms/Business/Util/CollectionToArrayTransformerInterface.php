<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Util;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;

interface CollectionToArrayTransformerInterface
{

    /**
     * @param ObjectCollection $orderItems
     *
     * @return SpySalesOrderItem[] $orderItems
     */
    public function transformCollectionToArray(ObjectCollection $orderItems);

}
