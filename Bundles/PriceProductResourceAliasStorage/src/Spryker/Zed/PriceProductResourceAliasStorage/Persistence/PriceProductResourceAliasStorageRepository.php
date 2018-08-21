<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PriceProductResourceAliasStorage\Persistence\PriceProductResourceAliasStoragePersistenceFactory getFactory()
 */
class PriceProductResourceAliasStorageRepository extends AbstractRepository implements PriceProductResourceAliasStorageRepositoryInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage[]
     */
    public function getPriceProductAbstractStorageEntities(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->getPriceProductAbstractPropelQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find()
            ->getData();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return string[]
     */
    public function getProductAbstractSkuList(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->getProductAbstractPropelQuery()
            ->filterByIdProductAbstract_In($productAbstractIds)
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductAbstractTableMap::COL_SKU => static::KEY_SKU])
            ->find()
            ->toArray(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage[]
     */
    public function getPriceProductConcreteStorageEntities(array $productConcreteIds): array
    {
        return $this->getFactory()
            ->getPriceProductConcretePropelQuery()
            ->filterByFkProduct_In($productConcreteIds)
            ->find()
            ->getData();
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return string[]
     */
    public function getProductConcreteSkuList(array $productConcreteIds): array
    {
        return $this->getFactory()
            ->getProductPropelQuery()
            ->filterByIdProduct_In($productConcreteIds)
            ->select([SpyProductTableMap::COL_ID_PRODUCT, SpyProductTableMap::COL_SKU => static::KEY_SKU])
            ->find()
            ->toArray(SpyProductTableMap::COL_ID_PRODUCT);
    }

    /**
     * @param int[] $priceProductStoreIds
     *
     * @return string[]
     */
    public function getProductConcreteSkuListByPriceProductStoreIds(array $priceProductStoreIds): array
    {
        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $findCriteria */
        $findCriteria = $this->getFactory()
        ->getPriceProductStorePropelQuery()
        ->filterByIdPriceProductStore_In($priceProductStoreIds)
        ->usePriceProductQuery()
            ->joinWithProduct()
        ->endUse()
        ->select([SpyProductTableMap::COL_ID_PRODUCT])
        ->addAsColumn(static::KEY_SKU, SpyProductTableMap::COL_SKU);

        return $findCriteria->find()
            ->toArray(SpyProductTableMap::COL_ID_PRODUCT);
    }
}
