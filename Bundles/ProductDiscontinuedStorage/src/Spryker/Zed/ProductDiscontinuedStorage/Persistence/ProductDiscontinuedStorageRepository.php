<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStoragePersistenceFactory getFactory()
 */
class ProductDiscontinuedStorageRepository extends AbstractRepository implements ProductDiscontinuedStorageRepositoryInterface
{
    /**
     * @param int[] $productDiscontinuedIds
     *
     * @return \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage[]
     */
    public function findProductDiscontinuedStorageEntitiesByIds(array $productDiscontinuedIds): array
    {
        if (!$productDiscontinuedIds) {
            return [];
        }

        return $this->getFactory()
            ->createProductDiscontinuedStoragePropelQuery()
            ->filterByFkProductDiscontinued_In($productDiscontinuedIds)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @return \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage[]
     */
    public function findAllProductDiscontinuedStorageEntities(): array
    {
        /** @var \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage[]|\Propel\Runtime\Collection\ObjectCollection $productDiscontinuedStorageEntities */
        $productDiscontinuedStorageEntities = $this->getFactory()
            ->createProductDiscontinuedStoragePropelQuery()
            ->find();

        if (!$productDiscontinuedStorageEntities->count()) {
            return [];
        }

        return $productDiscontinuedStorageEntities->getArrayCopy();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productDiscontinuedStorageEntityIds
     *
     * @return \Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer[]
     */
    public function findFilteredProductDiscontinuedStorageEntities(FilterTransfer $filterTransfer, array $productDiscontinuedStorageEntityIds = []): array
    {
        $query = $this->getFactory()
            ->createProductDiscontinuedStoragePropelQuery();

        if ($productDiscontinuedStorageEntityIds) {
            $query->filterByIdProductDiscontinuedStorage_In($productDiscontinuedStorageEntityIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }
}
