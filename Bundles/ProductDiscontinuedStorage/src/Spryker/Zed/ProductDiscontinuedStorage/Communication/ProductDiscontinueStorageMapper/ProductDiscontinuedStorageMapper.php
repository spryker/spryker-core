<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Communication\ProductDiscontinueStorageMapper;

use Generated\Shared\Transfer\SynchronizationDataTransfer;

class ProductDiscontinuedStorageMapper implements ProductDiscontinuedStorageMapperInterface
{
    /**
     * @param \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage[] $productDiscontinuedStorageEntities
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapProductDiscontinuedStorageEntitiesToSynchronizationDataTransfers(array $productDiscontinuedStorageEntities): array
    {
        $synchronizationDataTransfers = [];

        foreach ($productDiscontinuedStorageEntities as $productDiscontinuedStorageEntity) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            /** @var string $data */
            $data = $productDiscontinuedStorageEntity->getData();
            $synchronizationDataTransfer->setData($data);
            $synchronizationDataTransfer->setKey($productDiscontinuedStorageEntity->getKey());
            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }
}
