<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Grouper;

class ItemGrouper implements ItemGrouperInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function getItemTransferCollectionIndexedByIdSalesOrderItem(
        array $itemTransferCollection
    ): array {
        $itemTransferCollectionIndexedByIdSalesOrderItem = [];
        foreach ($itemTransferCollection as $itemTransfer) {
            $idSalesOrderItem = $itemTransfer->getIdSalesOrderItem();
            if (!$idSalesOrderItem) {
                continue;
            }

            $itemTransferCollectionIndexedByIdSalesOrderItem[$idSalesOrderItem] = $itemTransfer;
        }

        return $itemTransferCollectionIndexedByIdSalesOrderItem;
    }
}
