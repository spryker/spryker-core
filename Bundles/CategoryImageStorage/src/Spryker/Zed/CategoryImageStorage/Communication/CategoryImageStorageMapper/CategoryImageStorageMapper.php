<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Communication\CategoryImageStorageMapper;

use Generated\Shared\Transfer\CategoryImageStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;

class CategoryImageStorageMapper implements CategoryImageStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryImageStorageTransfer[] $categoryImageStorageTransfers
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapCategoryImageStorageTransfersCollectionToSynchronizationDataTransferCollection(array $categoryImageStorageTransfers): array
    {
        $synchronizationDataTransfers = [];

        foreach ($categoryImageStorageTransfers as $categoryImageStorageTransfer) {
            $synchronizationDataTransfers[] = $this->mapCategoryImageStorageTransferToSynchronizationDataTransfer(
                $categoryImageStorageTransfer,
                new SynchronizationDataTransfer()
            );
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageStorageTransfer $categoryImageStorageTransfer
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $synchronizationDataTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    protected function mapCategoryImageStorageTransferToSynchronizationDataTransfer(
        CategoryImageStorageTransfer $categoryImageStorageTransfer,
        SynchronizationDataTransfer $synchronizationDataTransfer
    ): SynchronizationDataTransfer {
        return $synchronizationDataTransfer
            ->setData($categoryImageStorageTransfer->getData())
            ->setKey($categoryImageStorageTransfer->getKey());
    }
}
