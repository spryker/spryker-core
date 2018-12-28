<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\OrderItem;

use ArrayObject;

class SalesOrderItemGrouper implements SalesOrderItemGrouperInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return array
     */
    public function getUniqueOrderItemsCollection(ArrayObject $itemTransfers): array
    {
        $existedOrderLines = [];
        foreach ($itemTransfers as $itemTransfer) {
            if (array_key_exists($itemTransfer->getSku(), $existedOrderLines)) {
                $newQuantity = $existedOrderLines[$itemTransfer->getSku()]['quantity'] + $itemTransfer->getQuantity();
                $existedOrderLines[$itemTransfer->getSku()]['quantity'] = $newQuantity;
                $newPrice = $itemTransfer->getSumPrice() * $newQuantity;
                $existedOrderLines[$itemTransfer->getSku()]['price'] = $newPrice;
                continue;
            }
            $existedOrderLines[$itemTransfer->getSku()] = [
                'name' => $itemTransfer->getName(),
                'quantity' => (int)$itemTransfer->getQuantity(),
                'price' => $itemTransfer->getSumPrice(),
                'productOptions' => $itemTransfer->getProductOptions(),
            ];
        }

        return $existedOrderLines;
    }
}
