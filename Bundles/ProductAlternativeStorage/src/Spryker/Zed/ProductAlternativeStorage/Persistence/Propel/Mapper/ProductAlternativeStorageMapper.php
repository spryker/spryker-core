<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage;
use Propel\Runtime\Collection\ObjectCollection;

class ProductAlternativeStorageMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage> $productAlternativeStorageEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function mapProductAlternativeStorageEntityCollectionToSynchronizationDataTransfers(
        ObjectCollection $productAlternativeStorageEntityCollection
    ): array {
        $synchronizationDataTransfers = [];

        foreach ($productAlternativeStorageEntityCollection as $productAlternativeStorageEntity) {
            $synchronizationDataTransfers[] = $this->mapProductAlternativeStorageEntityToSynchronizationDataTransfer(
                $productAlternativeStorageEntity,
                new SynchronizationDataTransfer()
            );
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage $productAlternativeStorageEntity
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $synchronizationDataTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    public function mapProductAlternativeStorageEntityToSynchronizationDataTransfer(
        SpyProductAlternativeStorage $productAlternativeStorageEntity,
        SynchronizationDataTransfer $synchronizationDataTransfer
    ): SynchronizationDataTransfer {
        /** @var string $data */
        $data = $productAlternativeStorageEntity->getData();

        return $synchronizationDataTransfer
            ->setData($data)
            ->setKey($productAlternativeStorageEntity->getKey());
    }
}
