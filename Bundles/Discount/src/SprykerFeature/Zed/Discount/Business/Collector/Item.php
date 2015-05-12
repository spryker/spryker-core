<?php

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\OrderItemsTransfer;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;

class Item implements CollectorInterface
{
    /**
     * @param DiscountableContainerInterface $container
     * @return array
     */
    public function collect(DiscountableContainerInterface $container)
    {
        $discountableItems = [];
        $items = $container->getItems();

        if ($items instanceof OrderItemsTransfer) {
            foreach ($items->getOrderItems() as $item) {
                $discountableItems[] = $item;
            }
        }

        return $discountableItems;
    }
}
