<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyProductReplacementStorageEntityTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductAlternative\Persistence\Map\SpyProductAlternativeTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStoragePersistenceFactory getFactory()
 */
class ProductAlternativeStorageRepository extends AbstractRepository implements ProductAlternativeStorageRepositoryInterface
{
    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer[]
     */
    public function findProductAlternativeStorageEntities(array $productIds): array
    {
        if (!$productIds) {
            return [];
        }
        $query = $this->getFactory()
            ->createProductAlternativeStoragePropelQuery()
            ->filterByFkProduct_In($productIds);

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @module Product
     *
     * @param int $idProduct
     *
     * @return string
     */
    public function findProductSkuById($idProduct): string
    {
        return $this->getFactory()
            ->getProductPropelQuery()
            ->filterByIdProduct($idProduct)
            ->select([SpyProductTableMap::COL_SKU])
            ->findOne();
    }

    /**
     * @module ProductAlternative
     *
     * @param int $idProduct
     *
     * @return int[]
     */
    public function findAbstractAlternativesIdsByConcreteProductId($idProduct): array
    {
        return $this->getFactory()
            ->getProductAlternativePropelQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductAbstractAlternative(null, Criteria::ISNOTNULL)
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT_ABSTRACT_ALTERNATIVE])
            ->find()
            ->toArray();
    }

    /**
     * @module ProductAlternative
     *
     * @param int $idProduct
     *
     * @return int[]
     */
    public function findConcreteAlternativesIdsByConcreteProductId($idProduct): array
    {
        return $this->getFactory()
            ->getProductAlternativePropelQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductConcreteAlternative(null, Criteria::ISNOTNULL)
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT_CONCRETE_ALTERNATIVE])
            ->find()
            ->toArray();
    }

    /**
     * @module Product
     *
     * @param int[] $productIds
     *
     * @return string[]
     */
    public function getIndexedProductConcreteIdToSkusByProductIds(array $productIds): array
    {
        return $this->getFactory()
            ->getProductPropelQuery()
            ->filterByIdProduct_In($productIds)
            ->addAsColumn(ProductConcreteTransfer::SKU, SpyProductTableMap::COL_SKU)
            ->select([SpyProductTableMap::COL_ID_PRODUCT, ProductConcreteTransfer::SKU])
            ->find()
            ->toArray(SpyProductTableMap::COL_ID_PRODUCT);
    }

    /**
     * @module Product
     *
     * @param int[] $productIds
     *
     * @return string[]
     */
    public function getIndexedProductAbstractIdToSkusByProductIds(array $productIds): array
    {
        return $this->getFactory()
            ->getProductAbstractPropelQuery()
            ->filterByIdProductAbstract_In($productIds)
            ->addAsColumn(ProductAbstractTransfer::SKU, SpyProductAbstractTableMap::COL_SKU)
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, ProductAbstractTransfer::SKU])
            ->find()
            ->toArray(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\SpyProductReplacementStorageEntityTransfer|null
     */
    public function findProductReplacementStorageEntitiesBySku(string $sku): ?SpyProductReplacementStorageEntityTransfer
    {
        $productReplacementStorageQuery = $this->getFactory()
            ->createProductReplacementStoragePropelQuery()
            ->filterBySku_Like($sku);

        return $this->buildQueryFromCriteria($productReplacementStorageQuery)->findOne();
    }

    /**
     * @module ProductAlternative
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getReplacementsByAbstractProductId(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->getProductAlternativePropelQuery()
            ->filterByFkProductAbstractAlternative($idProductAbstract)
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT])
            ->find()
            ->toArray();
    }

    /**
     * @module ProductAlternative
     *
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getReplacementsByConcreteProductId(int $idProductConcrete): array
    {
        return $this->getFactory()
            ->getProductAlternativePropelQuery()
            ->filterByFkProductConcreteAlternative($idProductConcrete)
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT])
            ->find()
            ->toArray();
    }
}
