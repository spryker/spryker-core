<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductQuantity\Persistence\ProductQuantityPersistenceFactory getFactory()
 */
class ProductQuantityRepository extends AbstractRepository implements ProductQuantityRepositoryInterface
{
    /**
     * @uses SpyProductQuery
     *
     * @param string[] $productSkus
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[]
     */
    public function findProductQuantityEntitiesByProductSku(array $productSkus): array
    {
        if (!$productSkus) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductQuantityQuery()
            ->joinWithProduct()
            ->useProductQuery()
                ->filterBySku_In($productSkus)
            ->endUse();

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[]
     */
    public function findProductQuantityEntitiesByProductIds(array $productIds): array
    {
        if (!$productIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductQuantityQuery()
            ->filterByFkProduct_In($productIds);

        return $this->buildQueryFromCriteria($query)->find();
    }
}
