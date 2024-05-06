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
use Propel\Runtime\Formatter\ObjectFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStoragePersistenceFactory getFactory()
 */
class ProductAlternativeStorageRepository extends AbstractRepository implements ProductAlternativeStorageRepositoryInterface
{
    /**
     * @param array<int> $productIds
     *
     * @return array<\Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage>
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
     * @return array<int>
     */
    public function findAbstractAlternativesIdsByConcreteProductId($idProduct): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productAbstractAlternativeIds */
        $productAbstractAlternativeIds = $this->getFactory()
            ->getProductAlternativePropelQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductAbstractAlternative(null, Criteria::ISNOTNULL)
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT_ABSTRACT_ALTERNATIVE])
            ->find();

        return $productAbstractAlternativeIds->toArray();
    }

    /**
     * @module ProductAlternative
     *
     * @param int $idProduct
     *
     * @return array<int>
     */
    public function findConcreteAlternativesIdsByConcreteProductId($idProduct): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productConcreteAlternativeIds */
        $productConcreteAlternativeIds = $this->getFactory()
            ->getProductAlternativePropelQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductConcreteAlternative(null, Criteria::ISNOTNULL)
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT_CONCRETE_ALTERNATIVE])
            ->find();

        return $productConcreteAlternativeIds->toArray();
    }

    /**
     * @module Product
     *
     * @param array<int> $productIds
     *
     * @return array<string>
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

        /** @var \Propel\Runtime\Collection\ArrayCollection $productConcreteIdToSkusByProductIds */
        $productConcreteIdToSkusByProductIds = $productQuery->select([
                    SpyProductTableMap::COL_ID_PRODUCT,
                    ProductConcreteTransfer::SKU,
                    ProductConcreteTransfer::FK_PRODUCT_ABSTRACT,
                    ProductConcreteTransfer::ABSTRACT_SKU,
            ])
            ->find();

        return $productConcreteIdToSkusByProductIds->toArray(SpyProductTableMap::COL_ID_PRODUCT);
    }

    /**
     * @module Product
     *
     * @param array<int> $productIds
     *
     * @return array<string>
     */
    public function getIndexedProductAbstractIdToSkusByProductIds(array $productIds): array
    {
        $productAbstractQuery = $this->getFactory()
            ->getProductAbstractPropelQuery();
        $productAbstractQuery->filterByIdProductAbstract_In($productIds)
            ->addAsColumn(ProductAbstractTransfer::SKU, SpyProductAbstractTableMap::COL_SKU);

        /** @var \Propel\Runtime\Collection\ArrayCollection $indexedProductAbstractIdToSkusByProductIds */
        $indexedProductAbstractIdToSkusByProductIds = $productAbstractQuery->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, ProductAbstractTransfer::SKU])
            ->find();

        return $indexedProductAbstractIdToSkusByProductIds->toArray(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * @module Product
     *
     * @param array<int> $productIds
     *
     * @return array<string>
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

        /** @var \Propel\Runtime\Collection\ArrayCollection $indexedProductConcreteIdToSkusByProductAbstractIds */
        $indexedProductConcreteIdToSkusByProductAbstractIds = $productAbstractQuery->select([
                    SpyProductTableMap::COL_ID_PRODUCT,
                    ProductConcreteTransfer::SKU,
                    ProductConcreteTransfer::FK_PRODUCT_ABSTRACT,
                    ProductConcreteTransfer::ABSTRACT_SKU,
            ])
            ->find();

        return $indexedProductConcreteIdToSkusByProductAbstractIds->toArray(SpyProductTableMap::COL_ID_PRODUCT);
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
     * @return array<int>
     */
    public function getReplacementsByAbstractProductId(int $idProductAbstract): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productAlternativeByProductIds */
        $productAlternativeByProductIds = $this->getFactory()
            ->getProductAlternativePropelQuery()
            ->filterByFkProductAbstractAlternative($idProductAbstract)
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT])
            ->find();

        return $productAlternativeByProductIds->toArray();
    }

    /**
     * @module ProductAlternative
     *
     * @param int $idProductConcrete
     *
     * @return array<int>
     */
    public function getReplacementsByConcreteProductId(int $idProductConcrete): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productAlternativeByConcreteProductIds */
        $productAlternativeByConcreteProductIds = $this->getFactory()
            ->getProductAlternativePropelQuery()
            ->filterByFkProductConcreteAlternative($idProductConcrete)
            ->select([SpyProductAlternativeTableMap::COL_FK_PRODUCT])
            ->find();

        return $productAlternativeByConcreteProductIds->toArray();
    }

    /**
     * @see \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface::getSynchronizationDataTransfersByFilterAndProductAlternativeStorageIds()
     *
     * @return array<\Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage>
     */
    public function findAllProductAlternativeStorageEntities(): array
    {
        return $this->getFactory()
            ->createProductAlternativeStoragePropelQuery()
            ->find()
            ->getArrayCopy();
    }

    /**
     * @see \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface::getSynchronizationDataTransfersByFilterAndProductAlternativeStorageIds()
     *
     * @param array<int> $productAlternativeStorageIds
     *
     * @return array<\Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage>
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
     * @see \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface::getSynchronizationDataTransfersByFilterAndProductReplacementForStorageIds()
     *
     * @return array<\Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage>
     */
    public function findAllProductReplacementForStorageEntities(): array
    {
        return $this->getFactory()
            ->createProductReplacementForStoragePropelQuery()
            ->find()
            ->getArrayCopy();
    }

    /**
     * @see \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface::getSynchronizationDataTransfersByFilterAndProductReplacementForStorageIds()
     *
     * @param array<int> $productReplacementForStorageIds
     *
     * @return array<\Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage>
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
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productAlternativeStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersByFilterAndProductAlternativeStorageIds(
        FilterTransfer $filterTransfer,
        array $productAlternativeStorageIds = []
    ): array {
        $query = $this->getFactory()
            ->createProductAlternativeStoragePropelQuery();

        if ($productAlternativeStorageIds !== []) {
            $query->filterByIdProductAlternativeStorage_In($productAlternativeStorageIds);
        }

        $productAlternativeStorageEntityCollection = $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(ObjectFormatter::class)
            ->find();

        return $this->getFactory()
            ->createProductAlternativeStorageMapper()
            ->mapProductAlternativeStorageEntityCollectionToSynchronizationDataTransfers($productAlternativeStorageEntityCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productReplacementForStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersByFilterAndProductReplacementForStorageIds(
        FilterTransfer $filterTransfer,
        array $productReplacementForStorageIds = []
    ): array {
        $query = $this->getFactory()
            ->createProductReplacementForStoragePropelQuery();

        if ($productReplacementForStorageIds !== []) {
            $query->filterByIdProductReplacementForStorage_In($productReplacementForStorageIds);
        }

        $productReplacementForStorageEntityCollection = $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(ObjectFormatter::class)
            ->find();

        return $this->getFactory()
            ->createProductReplacementStorageMapper()
            ->mapProductReplacementForStorageEntityCollectionToSynchronizationDataTransfers($productReplacementForStorageEntityCollection);
    }
}
