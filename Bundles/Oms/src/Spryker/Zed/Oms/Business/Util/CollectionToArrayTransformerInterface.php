<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Business\Util;

use Propel\Runtime\Collection\ObjectCollection;

interface CollectionToArrayTransformerInterface
{

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     */
    public function transformCollectionToArray(ObjectCollection $orderItems);

}
