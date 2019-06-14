<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Communication\ProductPackagingUnitStorageMapper;

use Generated\Shared\Transfer\SynchronizationDataTransfer;

class ProductPackagingUnitStorageMapper implements ProductPackagingUnitStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductAbstractPackagingStorageEntityTransfer[] $productAbstractPackagingStorageEntities
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductAbstractPackagingStorageEntitiesToSynchronizationDataTransfers(array $productAbstractPackagingStorageEntities): array
    {
        $synchronizationDataTransfers = [];

        foreach ($productAbstractPackagingStorageEntities as $productAbstractPackagingStorageEntity) {
            /** @var string $data */
            $data = $productAbstractPackagingStorageEntity->getData();
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            $synchronizationDataTransfer->setData($data);
            $synchronizationDataTransfer->setKey($productAbstractPackagingStorageEntity->getKey());
            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }
}
