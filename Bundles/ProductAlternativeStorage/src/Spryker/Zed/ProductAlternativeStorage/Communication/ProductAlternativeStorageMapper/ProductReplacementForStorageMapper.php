<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Communication\ProductAlternativeStorageMapper;

use Generated\Shared\Transfer\SynchronizationDataTransfer;

/**
 * @deprecated Will be removed without replacement.
 */
class ProductReplacementForStorageMapper implements ProductReplacementForStorageMapperInterface
{
    /**
     * @param array<\Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage> $productReplacementForStorageEntities
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
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
}
