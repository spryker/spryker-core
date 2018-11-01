<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStoragePersistenceFactory getFactory()
 */
class ProductQuantityStorageRepository extends AbstractRepository implements ProductQuantityStorageRepositoryInterface
{
    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer[]
     */
    public function findProductQuantityStorageEntitiesByProductIds(array $productIds): array
    {
        if (!$productIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductQuantityStorageQuery()
            ->filterByFkProduct_In($productIds);

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @return \Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer[]
     */
    public function findAllProductQuantityStorageEntities(): array
    {
        $query = $this->getFactory()->createProductQuantityStorageQuery();

        return $this->buildQueryFromCriteria($query)->find();
    }
}
