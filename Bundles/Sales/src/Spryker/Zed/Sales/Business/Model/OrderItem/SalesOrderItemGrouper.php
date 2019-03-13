<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\OrderItem;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Spryker\Zed\Sales\Dependency\Service\SalesToShipmentServiceInterface;

class SalesOrderItemGrouper implements SalesOrderItemGrouperInterface
{
    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getUniqueOrderItems(iterable $itemTransfers): ItemCollectionTransfer
    {
        $calculatedOrderLines = new ArrayObject();
        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->requireGroupKey();
            $key = $itemTransfer->getGroupKey();
            if (!isset($calculatedOrderLines[$key])) {
                $calculatedOrderLines[$key] = $itemTransfer;
                continue;
            }

            $calculatedOrderLines[$key] = $this->changeQuantityOfUniqueItem($calculatedOrderLines[$key], $itemTransfer);
        }

        return (new ItemCollectionTransfer())
            ->setItems($calculatedOrderLines);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $calculatedItem
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function changeQuantityOfUniqueItem(ItemTransfer $calculatedItem, ItemTransfer $itemTransfer): ItemTransfer
    {
        $calculatedItem->setQuantity($calculatedItem->getQuantity() + $itemTransfer->getQuantity());
        $calculatedItem->setSumPrice($calculatedItem->getSumPrice() + $itemTransfer->getSumPrice());

        return $calculatedItem;
    }
}
