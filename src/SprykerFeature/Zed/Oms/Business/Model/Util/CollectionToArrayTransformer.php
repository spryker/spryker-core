<?php
namespace SprykerFeature\Zed\Oms\Business\Model\Util;

use Propel\Runtime\Collection\ObjectCollection;

/**
 * Class CollectionToArrayTransformer
 * @package SprykerFeature\Zed\Oms\Business\Model\Util
 */
class CollectionToArrayTransformer implements CollectionToArrayTransformerInterface
{
    /**
     * @param ObjectCollection $orderItems
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem[] $orderItems
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
