<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesQuantity\Persistence\SalesQuantityPersistenceFactory getFactory()
 */
class SalesQuantityRepository extends AbstractRepository implements SalesQuantityRepositoryInterface
{
    /**
     * @param string $productConcreteSku
     *
     * @return bool
     */
    public function isProductQuantitySplittable(string $productConcreteSku): bool
    {
        return (bool)$this->getFactory()
            ->getProductPropelQuery()
            ->filterBySku($productConcreteSku)
            ->select(SpyProductTableMap::COL_IS_QUANTITY_SPLITTABLE)
            ->findOne();
    }

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<bool>
     */
    public function getIsProductQuantitySplittableByProductConcreteSkus(array $productConcreteSkus): array
    {
        $result = $this->getFactory()
            ->getProductPropelQuery()
            ->filterBySku_In($productConcreteSkus)
            ->select([SpyProductTableMap::COL_SKU, SpyProductTableMap::COL_IS_QUANTITY_SPLITTABLE])
            ->find()
            ->getArrayCopy();

        return $this->indexIsProductQuantitySplittableByProductConcreteSkus($result);
    }

    /**
     * @param array $data
     *
     * @return array<bool>
     */
    protected function indexIsProductQuantitySplittableByProductConcreteSkus(array $data): array
    {
        $indexedData = [];
        foreach ($data as $item) {
            $indexedData[$item[SpyProductTableMap::COL_SKU]] = $item[SpyProductTableMap::COL_IS_QUANTITY_SPLITTABLE];
        }

        return $indexedData;
    }
}
