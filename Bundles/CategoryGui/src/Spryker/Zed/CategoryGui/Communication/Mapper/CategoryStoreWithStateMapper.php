<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\StoreWithStateCollectionTransfer;
use Generated\Shared\Transfer\StoreWithStateTransfer;

class CategoryStoreWithStateMapper implements CategoryStoreWithStateMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer[] $categoryStoreRelatedTransfers
     *
     * @return \Generated\Shared\Transfer\StoreWithStateCollectionTransfer
     */
    public function mapStoresWithCategoryStoreRelatedTransfersToStoreWithStateCollection(
        array $storeTransfers,
        array $categoryStoreRelatedTransfers
    ): StoreWithStateCollectionTransfer {
        $categoryStoreRelatedIds = $this->extractStoreIdsFromStoreCollection($categoryStoreRelatedTransfers);

        $stores = new ArrayObject();
        foreach ($storeTransfers as $storeTransfer) {
            $stores->append(
                (new StoreWithStateTransfer())
                    ->fromArray($storeTransfer->toArray(), true)
                    ->setIsActive($this->isStoreEnable($storeTransfer, $categoryStoreRelatedIds))
            );
        }

        return (new StoreWithStateCollectionTransfer())
            ->setStoresWithState($stores);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int[] $categoryStoreIds
     *
     * @return bool
     */
    protected function isStoreEnable(StoreTransfer $storeTransfer, array $categoryStoreIds): bool
    {
        return ($categoryStoreIds !== [] && in_array($storeTransfer->getIdStoreOrFail(), $categoryStoreIds));
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return int[]
     */
    protected function extractStoreIdsFromStoreCollection(array $storeTransfers): array
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStoreOrFail();
        }, $storeTransfers);
    }
}
