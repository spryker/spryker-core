<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Grouper;

use ArrayObject;
use Generated\Shared\Transfer\PickingListCollectionTransfer;

interface PickingListGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\PickingListTransfer>
     */
    public function getPickingListTransferCollectionIndexedByUuid(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): array;

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\PickingListItemTransfer>
     */
    public function getPickingListItemTransferCollectionIndexedByUuid(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): array;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\StockTransfer> $stockTransferCollection
     *
     * @return array<int, \Generated\Shared\Transfer\StockTransfer>
     */
    public function getStockTransferCollectionIndexedByIdWarehouse(ArrayObject $stockTransferCollection): array;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection
     *
     * @return array<int, list<\Generated\Shared\Transfer\ItemTransfer>>
     */
    public function getItemTransferCollectionGroupedByIdWarehouse(
        ArrayObject $itemTransferCollection
    ): array;
}
