<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Extractor;

use ArrayObject;
use Generated\Shared\Transfer\PickingListCollectionTransfer;

class PickingListExtractor implements PickingListExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return list<string>
     */
    public function extraWarehouseUuidsFromPickingListCollection(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): array {
        $warehouseUuids = [];
        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            if (!$pickingListTransfer->getWarehouse()) {
                continue;
            }

            $warehouseUuids[] = $pickingListTransfer->getWarehouseOrFail()->getUuidOrFail();
        }

        return array_filter(array_unique($warehouseUuids));
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return list<string>
     */
    public function extraUserUuidsFromPickingListCollection(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): array {
        $userUuids = [];
        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            if (!$pickingListTransfer->getUser()) {
                continue;
            }

            $userUuids[] = $pickingListTransfer->getUserOrFail()->getUuidOrFail();
        }

        return array_filter(array_unique($userUuids));
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     *
     * @return list<string>
     */
    public function extractPickingListUuids(ArrayObject $pickingListTransfers): array
    {
        $pickingListUuids = [];
        /** @var \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer */
        foreach ($pickingListTransfers as $pickingListTransfer) {
            if (!$pickingListTransfer->getUuid()) {
                continue;
            }
            $pickingListUuids[] = $pickingListTransfer->getUuidOrFail();
        }

        return $pickingListUuids;
    }
}
