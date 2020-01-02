<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Communication\ProductAlternativeStorageMapper;

use Generated\Shared\Transfer\SynchronizationDataTransfer;

class ProductReplacementForStorageMapper implements ProductReplacementForStorageMapperInterface
{
    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage[] $productReplacementForStorageEntities
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductReplacementForStorageEntitiesToSynchronizationDataTransfers(array $productReplacementForStorageEntities): array
    {
        $synchronizationDataTransfers = [];

        foreach ($productReplacementForStorageEntities as $productReplacementForStorageEntity) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            /** @var string $data */
            $data = $productReplacementForStorageEntity->getData();
            $synchronizationDataTransfer->setData($data);
            $synchronizationDataTransfer->setKey($productReplacementForStorageEntity->getKey());
            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductReplacementForStorageEntityTransfer[] $productReplacementForStorageEntityTransfers
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductReplacementForStorageEntityTransfersToSynchronizationDataTransfers(array $productReplacementForStorageEntityTransfers): array
    {
        $synchronizationDataTransfers = [];

        foreach ($productReplacementForStorageEntityTransfers as $productReplacementForStorageEntity) {
            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())
                ->setData($productReplacementForStorageEntity->getData())
                ->setKey($productReplacementForStorageEntity->getKey());
        }

        return $synchronizationDataTransfers;
    }
}
