<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Sales\Code;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Cart\Transfer\ItemCollectionInterface;

/**
 * @TODO Validate cross-bundle Dependencies
 */
abstract class AbstractItemGrouper
{

    const GROUP_KEY_SKU = 'Sku';
    const GROUP_KEY_UNIQUE_IDENTIFIER = 'UniqueIdentifier';

    /**
     * @param \Spryker\Shared\Cart\Transfer\ItemCollectionInterface $items
     *
     * @return \Spryker\Shared\Cart\Transfer\ItemCollectionInterface
     */
    public function groupItemsByUniqueId(ItemCollectionInterface $items)
    {
        return $this->groupItemsByKey($items, self::GROUP_KEY_UNIQUE_IDENTIFIER);
    }

    /**
     * This Method is not Options aware. Use with caution
     *
     * @param \Spryker\Shared\Cart\Transfer\ItemCollectionInterface $items
     *
     * @return \Spryker\Shared\Cart\Transfer\ItemCollectionInterface
     */
    public function groupItemsBySku(ItemCollectionInterface $items)
    {
        return $this->groupItemsByKey($items, self::GROUP_KEY_SKU);
    }

    /**
     * @param \Spryker\Shared\Cart\Transfer\ItemCollectionInterface $items
     * @param string $key
     *
     * @return \Spryker\Shared\Cart\Transfer\ItemCollectionInterface
     */
    protected function groupItemsByKey(ItemCollectionInterface $items, $key)
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer[] $index */
        $index = [];
        $methodName = 'get' . ucfirst($key);

        /** @var \Generated\Shared\Transfer\ItemTransfer $item */
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
