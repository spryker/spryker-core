<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Discount\OrderInterface;
use Generated\Shared\Discount\OrderItemsInterface;

class Item implements CollectorInterface
{
    /**
     * @param OrderInterface $container
     * @return array
     */
    public function collect(OrderInterface $container)
    {
        $discountableItems = [];
        $items = $container->getItems();

        if ($items instanceof OrderItemsInterface) {
            foreach ($items->getOrderItems() as $item) {
                $discountableItems[] = $item;
            }
        }

        return $discountableItems;
    }
}
