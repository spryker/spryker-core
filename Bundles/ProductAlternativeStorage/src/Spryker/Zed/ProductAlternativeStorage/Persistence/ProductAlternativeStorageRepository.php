<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStoragePersistenceFactory getFactory()
 */
class ProductAlternativeStorageRepository extends AbstractRepository implements ProductAlternativeStorageRepositoryInterface
{
    /**
     * @param int[] $productAlternativeIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer[]
     */
    public function findProductAlternativeStorageEntities(array $productAlternativeIds): array
    {
        if (!$productAlternativeIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductAlternativeStorageQuery()
            ->filterByFkProductAlternative_In($productAlternativeIds);

        return $this->buildQueryFromCriteria($query)->find();
    }
}
