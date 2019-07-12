<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * @deprecated Not used any more.
 */
class ProductOptionItemSorter implements ProductOptionItemSorterInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function sortItemsBySkuAndOptions(OrderTransfer $orderTransfer)
    {
        $items = $orderTransfer->getItems();

        $itemsWithOptions = [];
        $itemsWithoutOptions = [];

        foreach ($items as $itemTransfer) {
            if (!$itemTransfer->getProductOptions()->count()) {
                $itemsWithoutOptions[] = $itemTransfer;

                continue;
            }

            $itemsWithOptions[] = $itemTransfer;
        }

        $itemsWithOptions = $this->sortItemsBySku($itemsWithOptions);
        $itemsWithoutOptions = $this->sortItemsBySku($itemsWithoutOptions);

        $items = $this->mergeItems($itemsWithoutOptions, $itemsWithOptions);

        $orderTransfer->setItems(new ArrayObject($items));

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return array
     */
    protected function sortItemsBySku(array $items)
    {
        usort($items, function (ItemTransfer $item1, ItemTransfer $item2) {
            if ($item1->getSku() !== $item2->getSku()) {
                return $item1->getSku() > $item2->getSku();
            }

            return $item1->getIdSalesOrderItem() > $item2->getIdSalesOrderItem();
        });

        return $items;
    }

    /**
     * @param array $items1
     * @param array $items2
     *
     * @return array
     */
    protected function mergeItems(array $items1, array $items2)
    {
        foreach ($items2 as $item) {
            $items1[] = $item;
        }

        return $items1;
    }
}
