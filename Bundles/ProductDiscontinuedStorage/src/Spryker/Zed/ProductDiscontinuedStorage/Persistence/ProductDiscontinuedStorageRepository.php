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
     * @return \Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer[]
     */
    public function findProductDiscontinuedStorageEntitiesByIds(array $productDiscontinuedIds): array
    {
        if (!$productDiscontinuedIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductDiscontinuedStorageQuery()
            ->filterByFkProductDiscontinued_In($productDiscontinuedIds);

        return $this->buildQueryFromCriteria($query)->find();
    }
}
