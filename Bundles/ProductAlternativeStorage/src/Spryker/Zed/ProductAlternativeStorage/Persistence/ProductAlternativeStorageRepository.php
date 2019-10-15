<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductAlternative\Persistence\Map\SpyProductAlternativeTableMap;
use Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage;
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
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage[]
     */
    public function findProductAlternativeStorageEntities(array $productIds): array
    {
        if (!$productIds) {
            return [];
        }

        return $this->getFactory()
            ->createProductAlternativeStoragePropelQuery()
            ->filterByFkProduct_In($productIds)
            ->find()
            ->getArrayCopy();
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
        return (string)$this->getFactory()
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
        $productQuery = $this->getFactory()
            ->getProductPropelQuery()
            ->joinWithSpyProductAbstract();
        $productQuery->filterByIdProduct_In($productIds)
            ->addAsColumn(ProductConcreteTransfer::SKU, SpyProductTableMap::COL_SKU)
            ->addAsColumn(ProductConcreteTransfer::FK_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->addAsColumn(ProductConcreteTransfer::ABSTRACT_SKU, SpyProductAbstractTableMap::COL_SKU);

        return $productQuery->select([
                    SpyProductTableMap::COL_ID_PRODUCT,
                    ProductConcreteTransfer::SKU,
                    ProductConcreteTransfer::FK_PRODUCT_ABSTRACT,
                    ProductConcreteTransfer::ABSTRACT_SKU,
            ])
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
        $productAbstractQuery = $this->getFactory()
            ->getProductAbstractPropelQuery();
        $productAbstractQuery->filterByIdProductAbstract_In($productIds)
            ->addAsColumn(ProductAbstractTransfer::SKU, SpyProductAbstractTableMap::COL_SKU);

        return $productAbstractQuery->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, ProductAbstractTransfer::SKU])
            ->find()
            ->toArray(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * @module Product
     *
     * @param int[] $productIds
     *
     * @return string[]
     */
    public function getIndexedProductConcreteIdToSkusByProductAbstractIds(array $productIds): array
    {
        $productAbstractQuery = $this->getFactory()
            ->getProductPropelQuery()
            ->joinWithSpyProductAbstract();
        $productAbstractQuery->filterByFkProductAbstract_In($productIds)
            ->addAsColumn(ProductConcreteTransfer::SKU, SpyProductTableMap::COL_SKU)
            ->addAsColumn(ProductConcreteTransfer::FK_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->addAsColumn(ProductConcreteTransfer::ABSTRACT_SKU, SpyProductAbstractTableMap::COL_SKU);

        return $productAbstractQuery->select([
                    SpyProductTableMap::COL_ID_PRODUCT,
                    ProductConcreteTransfer::SKU,
                    ProductConcreteTransfer::FK_PRODUCT_ABSTRACT,
                    ProductConcreteTransfer::ABSTRACT_SKU,
            ])
            ->find()
            ->toArray(SpyProductTableMap::COL_ID_PRODUCT);
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage|null
     */
    public function findProductReplacementStorageEntitiesBySku(string $sku): ?SpyProductReplacementForStorage
    {
        return $this->getFactory()
            ->createProductReplacementForStoragePropelQuery()
            ->filterBySku_Like($sku)
            ->findOne();
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

    /**
     * @deprecated Use getAllProductAlternativeStorageByFilter instead.
     *
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage[]
     */
    public function findAllProductAlternativeStorageEntities(): array
    {
        return $this->getFactory()
            ->createProductAlternativeStoragePropelQuery()
            ->find()
            ->getArrayCopy();
    }

    /**
     * @deprecated Use getProductAlternativeStorageByFilter instead.
     *
     * @param int[] $productAlternativeStorageIds
     *
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage[]
     */
    public function findProductAlternativeStorageEntitiesByIds(array $productAlternativeStorageIds): array
    {
        return $this->getFactory()
            ->createProductAlternativeStoragePropelQuery()
            ->filterByIdProductAlternativeStorage_In($productAlternativeStorageIds)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage[]
     */
    public function findAllProductReplacementForStorageEntities(): array
    {
        return $this->getFactory()
            ->createProductReplacementForStoragePropelQuery()
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param int[] $productReplacementForStorageIds
     *
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage[]
     */
    public function findProductReplacementForStorageEntitiesByIds(array $productReplacementForStorageIds): array
    {
        return $this->getFactory()
            ->createProductReplacementForStoragePropelQuery()
            ->filterByIdProductReplacementForStorage_In($productReplacementForStorageIds)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @module ProductAlternative
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage[]
     */
    public function getAllProductAlternativeStorageByFilter(FilterTransfer $filterTransfer): array
    {
        return $this->getFactory()
            ->createProductAlternativeStoragePropelQuery()
            ->limit($filterTransfer->getLimit())
            ->offset($filterTransfer->getOffset())
            ->find()
            ->getArrayCopy();
    }

    /**
     * @module ProductAlternative
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productAlternativeStorageIds
     *
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage[]
     */
    public function getProductAlternativeStorageByFilter(FilterTransfer $filterTransfer, array $productAlternativeStorageIds): array
    {
        return $this->getFactory()
            ->createProductAlternativeStoragePropelQuery()
            ->filterByIdProductAlternativeStorage_In($productAlternativeStorageIds)
            ->limit($filterTransfer->getLimit())
            ->offset($filterTransfer->getOffset())
            ->find()
            ->getArrayCopy();
    }
}
