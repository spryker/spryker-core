<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer;
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
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer|null
     */
    public function findProductAlternativeStorageEntity($idProduct): ?SpyProductAlternativeStorageEntityTransfer
    {
        $query = $this->getFactory()
            ->createProductAlternativeStorageQuery()
            ->filterByFkProduct($idProduct);

        return $this->buildQueryFromCriteria($query)->findOne();
    }

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return string
     */
    public function findProductSkuById($idProduct): string
    {
        return $this
            ->getFactory()
            ->getProductQuery()
            ->filterByIdProduct($idProduct)
            ->findOne()
            ->getSku();
    }

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return int[]
     */
    public function findAbstractAlternativesIdsByConcreteProductId($idProduct): array
    {
        return $this->getFactory()
            ->getProductAlternativeQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductAbstractAlternative(null, Criteria::ISNOTNULL)
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT_ABSTRACT_ALTERNATIVE])
            ->find()
            ->toArray();
    }

    /**
     * @param int $idProduct
     *
     * @return int[]
     */
    public function findConcreteAlternativesIdsByConcreteProductId($idProduct): array
    {
        return $this->getFactory()
            ->getProductAlternativeQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductConcreteAlternative(null, Criteria::ISNOTNULL)
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT_CONCRETE_ALTERNATIVE])
            ->find()
            ->toArray();
    }

    /**
     * @api
     *
     * @param int[] $productIds
     *
     * @return string[]
     */
    public function getIndexedProductConcreteIdToSkusByProductIds(array $productIds): array
    {
        return $this->getFactory()
            ->getProductQuery()
            ->filterByIdProduct_In($productIds)
            ->addAsColumn(ProductConcreteTransfer::SKU, SpyProductTableMap::COL_SKU)
            ->select([SpyProductTableMap::COL_ID_PRODUCT, ProductConcreteTransfer::SKU])
            ->find()
            ->toArray(SpyProductTableMap::COL_ID_PRODUCT);
    }

    /**
     * @api
     *
     * @param int[] $productIds
     *
     * @return string[]
     */
    public function getIndexedProductAbstractIdToSkusByProductIds(array $productIds): array
    {
        return $this->getFactory()
            ->getProductAbstractQuery()
            ->filterByIdProductAbstract_In($productIds)
            ->addAsColumn(ProductAbstractTransfer::SKU, SpyProductAbstractTableMap::COL_SKU)
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, ProductAbstractTransfer::SKU])
            ->find()
            ->toArray(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\SpyProductReplacementStorageEntityTransfer|null
     */
    public function findProductReplacementStorageEntitiesBySku(string $sku): ?SpyProductReplacementStorageEntityTransfer
    {
        $productReplacementStorageQuery = $this->getFactory()
            ->createProductReplacementStorageQuery()
            ->filterBySku_Like($sku);

        return $this->buildQueryFromCriteria($productReplacementStorageQuery)->findOne();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getReplacementsByAbstractProductId(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->getProductAlternativeQuery()
            ->filterByFkProductAbstractAlternative($idProductAbstract)
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT])
            ->find()
            ->toArray();
    }

    /**
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getReplacementsByConcreteProductId(int $idProductConcrete): array
    {
        return $this->getFactory()
            ->getProductAlternativeQuery()
            ->filterByFkProductConcreteAlternative($idProductConcrete)
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT])
            ->find()
            ->toArray();
    }
}
