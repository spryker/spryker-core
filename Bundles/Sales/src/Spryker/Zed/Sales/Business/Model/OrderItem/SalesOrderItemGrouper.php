<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\OrderItem;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class SalesOrderItemGrouper implements SalesOrderItemGrouperInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getUniqueOrderItems(ArrayObject $itemTransfers): ItemCollectionTransfer
    {
        $existedOrderLines = new ArrayObject();
        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->requireSku();
            if (isset($existedOrderLines[$itemTransfer->getSku()])) {
                $existedOrderLines = $this->changeQuantityOfUniqueItem($existedOrderLines, $itemTransfer);
                continue;
            }

            $existedOrderLines = $this->addItemToUniqueItemsArray($existedOrderLines, $itemTransfer);
        }

        $itemCollectionTransfer = (new ItemCollectionTransfer())
            ->setItems($existedOrderLines);

        return $itemCollectionTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $existedOrderLines
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function changeQuantityOfUniqueItem(ArrayObject $existedOrderLines, ItemTransfer $itemTransfer): ArrayObject
    {
        $sku = $itemTransfer->getSku();
        $newQuantity = $existedOrderLines[$sku]['quantity'] + $itemTransfer->getQuantity();
        $existedOrderLines[$sku]['quantity'] = $newQuantity;
        $newPrice = $itemTransfer->getSumPrice() * $newQuantity;
        $existedOrderLines[$sku]['price'] = $newPrice;

        return $existedOrderLines;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $existedOrderLines
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function addItemToUniqueItemsArray(ArrayObject $existedOrderLines, ItemTransfer $itemTransfer): ArrayObject
    {
        $existedOrderLines[$itemTransfer->getSku()] = [
            'name' => $itemTransfer->getName(),
            'quantity' => (int)$itemTransfer->getQuantity(),
            'price' => $itemTransfer->getSumPrice(),
            'productOptions' => $itemTransfer->getProductOptions(),
        ];

        return $existedOrderLines;
    }
}
