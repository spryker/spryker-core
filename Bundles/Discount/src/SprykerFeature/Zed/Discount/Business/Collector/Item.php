<?php

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\Discount\DependencyDiscountableContainerInterfaceTransfer;

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

        foreach ($items as $item) {
            $discountableItems[] = $item;
        }

        return $discountableItems;
    }
}
