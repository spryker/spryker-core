<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Sales\Code;

use Spryker\Shared\Sales\Transfer\OrderItem;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * @TODO Validate cross-bundle Dependencies
 */
abstract class AbstractItemGrouper
{

    const GROUP_KEY_SKU = 'Sku';
    const GROUP_KEY_UNIQUE_IDENTIFIER = 'UniqueIdentifier';

    /**
     * @param OrderItemCollection $items
     *
     * @return OrderItemCollection
     */
    public function groupItemsByUniqueId(OrderItemCollection $items)
    {
        return $this->groupItemsByKey($items, self::GROUP_KEY_UNIQUE_IDENTIFIER);
    }

    /**
     * This Method is not Options aware. Use with caution
     *
     * @param OrderItemCollection $items
     *
     * @return OrderItemCollection
     */
    public function groupItemsBySku(OrderItemCollection $items)
    {
        return $this->groupItemsByKey($items, self::GROUP_KEY_SKU);
    }

    /**
     * @param OrderItemCollection $items
     * @param string $key
     *
     * @return OrderItemCollection
     */
    protected function groupItemsByKey(OrderItemCollection $items, $key)
    {
        $index = [];
        $methodName = 'get' . ucfirst($key);

        /** @var OrderItem $item */
        foreach ($items as $item) {
            $groupKey = $item->$methodName();
            if (isset($index[$groupKey])) {
                $index[$groupKey]->setQuantity($index[$groupKey]->getQuantity() + 1);
                $index[$groupKey]->setGrossPrice($index[$groupKey]->getGrossPrice() + $item->getGrossPrice());
                $index[$groupKey]->setPriceToPay($index[$groupKey]->getPriceToPay() + $item->getPriceToPay());
            } else {
                $newItem = clone $item;
                $newItem->setUnitGrossPrice($newItem->getGrossPrice());
                $newItem->setUnitPriceToPay($newItem->getPriceToPay());
                $index[$groupKey] = $newItem;
            }
        }
        $transferItems = new ItemTransfer();
        $transferItems->fromArray($index);

        return $transferItems;
    }

}
