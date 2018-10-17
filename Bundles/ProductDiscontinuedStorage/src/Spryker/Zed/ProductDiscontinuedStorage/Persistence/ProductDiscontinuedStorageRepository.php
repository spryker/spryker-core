<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Persistence;

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

        /** @var \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage[]|\Propel\Runtime\Collection\ObjectCollection $productDiscontinuedStorageEntities */
        $productDiscontinuedStorageEntities = $this->getFactory()
            ->createProductDiscontinuedStoragePropelQuery()
            ->filterByFkProductDiscontinued_In($productDiscontinuedIds)
            ->find();

        if (!$productDiscontinuedStorageEntities->count()) {
            return [];
        }

        return $productDiscontinuedStorageEntities->getArrayCopy();
    }
}
