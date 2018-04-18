<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Persistence;

use Generated\Shared\Transfer\ProductQuantityTransfer;
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
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
     */
    public function findProductQuantityTransfersByProductSku(array $productSkus): array
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

        $result = $this->buildQueryFromCriteria($query)->find();

        $mapper = $this->getFactory()->createProductQuantityMapper();
        $productQuantityCollection = [];
        foreach ($result as $item) {
            $productQuantityCollection[] = $mapper->mapProductQuantityTransfer($item, new ProductQuantityTransfer());
        }

        return $productQuantityCollection;
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
     */
    public function findProductQuantityTransfersByProductIds(array $productIds): array
    {
        if (!$productIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductQuantityQuery()
            ->filterByFkProduct_In($productIds);

        $result = $this->buildQueryFromCriteria($query)->find();

        $mapper = $this->getFactory()->createProductQuantityMapper();
        $productQuantityCollection = [];
        foreach ($result as $item) {
            $productQuantityCollection[] = $mapper->mapProductQuantityTransfer($item, new ProductQuantityTransfer());
        }

        return $productQuantityCollection;
    }
}
