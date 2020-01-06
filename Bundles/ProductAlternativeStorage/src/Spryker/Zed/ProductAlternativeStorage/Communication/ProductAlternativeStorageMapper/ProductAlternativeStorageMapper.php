<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Communication\ProductAlternativeStorageMapper;

use Generated\Shared\Transfer\SynchronizationDataTransfer;

class ProductAlternativeStorageMapper implements ProductAlternativeStorageMapperInterface
{
    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage[] $productAlternativeStorageEntities
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductAlternativeStorageEntitiesToSynchronizationDataTransfers(array $productAlternativeStorageEntities): array
    {
        $synchronizationDataTransfers = [];

        foreach ($productAlternativeStorageEntities as $productAlternativeStorageEntity) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            /** @var string $data */
            $data = $productAlternativeStorageEntity->getData();
            $synchronizationDataTransfer->setData($data);
            $synchronizationDataTransfer->setKey($productAlternativeStorageEntity->getKey());
            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeStorageTransfer[] $productAlternativeStorageTransfers
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductAlternativeStorageTransfersToSynchronizationDataTransfers(array $productAlternativeStorageTransfers): array
    {
        $synchronizationDataTransfers = [];

        foreach ($productAlternativeStorageTransfers as $productAlternativeStorageTransfer) {
            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())
                ->setData($productAlternativeStorageTransfer->getData())
                ->setKey($productAlternativeStorageTransfer->getKey());
        }

        return $synchronizationDataTransfers;
    }
}
