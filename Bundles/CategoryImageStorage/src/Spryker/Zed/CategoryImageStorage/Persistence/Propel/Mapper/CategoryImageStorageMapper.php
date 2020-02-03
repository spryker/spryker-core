<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage;
use Propel\Runtime\Collection\ObjectCollection;

class CategoryImageStorageMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage[] $categoryImageStorageEntityCollection
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapCategoryImageStorageEntityCollectionToSynchronizationDataTransfers(ObjectCollection $categoryImageStorageEntityCollection): array
    {
        $synchronizationDataTransfers = [];

        foreach ($categoryImageStorageEntityCollection as $categoryImageStorageEntity) {
            $synchronizationDataTransfers[] = $this->mapCategoryImageStorageEntityToSynchronizationDataTransfer(
                $categoryImageStorageEntity,
                new SynchronizationDataTransfer()
            );
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage $categoryImageStorageEntity
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $synchronizationDataTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    public function mapCategoryImageStorageEntityToSynchronizationDataTransfer(
        SpyCategoryImageStorage $categoryImageStorageEntity,
        SynchronizationDataTransfer $synchronizationDataTransfer
    ): SynchronizationDataTransfer {
        /** @var string $data */
        $data = $categoryImageStorageEntity->getData();

        return $synchronizationDataTransfer
            ->setData($data)
            ->setKey($categoryImageStorageEntity->getKey());
    }
}
