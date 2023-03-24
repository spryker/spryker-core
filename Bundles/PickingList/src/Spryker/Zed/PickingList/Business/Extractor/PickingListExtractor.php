<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Extractor;

use Generated\Shared\Transfer\PickingListCollectionTransfer;

class PickingListExtractor implements PickingListExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return array<string>
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
     * @return array<string>
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
}
