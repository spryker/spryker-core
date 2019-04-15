<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Product\Persistence\ProductPersistenceFactory getFactory()
 */
class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    public const KEY_FILTERED_PRODUCTS_RESULT = 'result';
    public const KEY_FILTERED_PRODUCTS_PRODUCT_NAME = 'name';

    /**
     * @param string $productConcreteSku
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer|null
     */
    public function findProductConcreteBySku(string $productConcreteSku): ?SpyProductEntityTransfer
    {
        $productQuery = $this->getFactory()
            ->createProductQuery()
            ->joinWithSpyProductAbstract()
            ->filterBySku($productConcreteSku);

        return $this->buildQueryFromCriteria($productQuery)->findOne();
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer|null
     */
    public function findProductConcreteById(int $idProductConcrete): ?SpyProductEntityTransfer
    {
        $productQuery = $this->getFactory()
            ->createProductQuery()
            ->joinWithSpyProductAbstract()
            ->filterByIdProduct($idProductConcrete);

        return $this->buildQueryFromCriteria($productQuery)->findOne();
    }

    /**
     * @param string $search
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $limit
     *
     * @return array
     */
    public function findProductAbstractDataBySkuOrLocalizedName(string $search, LocaleTransfer $localeTransfer, int $limit): array
    {
        $criteria = new Criteria();
        $skuLikeCriteria = $criteria->getNewCriterion(
            SpyProductAbstractTableMap::COL_SKU,
            '%' . $search . '%',
            Criteria::LIKE
        );

        $productAbstractQuery = $this->getFactory()
            ->createProductAbstractQuery();
        $productAbstractQuery->leftJoinSpyProductAbstractLocalizedAttributes()
            ->addJoinCondition(
                'SpyProductAbstractLocalizedAttributes',
                sprintf('SpyProductAbstractLocalizedAttributes.fk_locale = %d', $localeTransfer->getIdLocale())
            )
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::KEY_FILTERED_PRODUCTS_PRODUCT_NAME)
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, static::KEY_FILTERED_PRODUCTS_RESULT)
            ->where('lower(' . SpyProductAbstractLocalizedAttributesTableMap::COL_NAME . ') like ?', '%' . mb_strtolower($search) . '%')
            ->addOr($skuLikeCriteria);
        $productAbstractQuery->limit($limit)
            ->select([
                static::KEY_FILTERED_PRODUCTS_RESULT,
                static::KEY_FILTERED_PRODUCTS_PRODUCT_NAME,
            ])->addAscendingOrderByColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME);

        return $this->collectFilteredResults(
            $productAbstractQuery->find()->toArray()
        );
    }

    /**
     * @param string $search
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $limit
     *
     * @return array
     */
    public function findProductConcreteDataBySkuOrLocalizedName(string $search, LocaleTransfer $localeTransfer, int $limit): array
    {
        $criteria = new Criteria();
        $skuLikeCriteria = $criteria->getNewCriterion(
            SpyProductTableMap::COL_SKU,
            '%' . $search . '%',
            Criteria::LIKE
        );

        $productConcreteQuery = $this->getFactory()
            ->createProductQuery();
        $productConcreteQuery->leftJoinSpyProductLocalizedAttributes()
            ->addJoinCondition(
                'SpyProductLocalizedAttributes',
                sprintf('SpyProductLocalizedAttributes.fk_locale = %d', $localeTransfer->getIdLocale())
            )
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, static::KEY_FILTERED_PRODUCTS_PRODUCT_NAME)
            ->withColumn(SpyProductTableMap::COL_SKU, static::KEY_FILTERED_PRODUCTS_RESULT)
            ->where('lower(' . SpyProductLocalizedAttributesTableMap::COL_NAME . ') like ?', '%' . mb_strtolower($search) . '%')
            ->addOr($skuLikeCriteria);
        $productConcreteQuery->limit($limit)
            ->select([
                static::KEY_FILTERED_PRODUCTS_RESULT,
                static::KEY_FILTERED_PRODUCTS_PRODUCT_NAME,
            ]);

        return $this->collectFilteredResults(
            $productConcreteQuery->find()->toArray()
        );
    }

    /**
     * @param int $idProductConcrete
     *
     * @return int|null
     */
    public function findProductAbstractIdByConcreteId(int $idProductConcrete): ?int
    {
        $productConcrete = $this->getFactory()
            ->createProductQuery()
            ->filterByIdProduct($idProductConcrete)
            ->findOne();

        if (!$productConcrete) {
            return null;
        }

        return $productConcrete->getFkProductAbstract();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findProductConcreteIdsByAbstractProductId(int $idProductAbstract): array
    {
        $productConcreteQuery = $this->getFactory()
            ->createProductQuery();
        $productConcreteIds = $productConcreteQuery
            ->filterByFkProductAbstract($idProductAbstract)
            ->select([SpyProductTableMap::COL_ID_PRODUCT])
            ->find();

        if (!$productConcreteIds) {
            return [];
        }

        return $productConcreteIds->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool
     */
    public function isProductConcreteActive(ProductConcreteTransfer $productConcreteTransfer): bool
    {
        return $this->getFactory()
            ->createProductQuery()
            ->findOneBySku($productConcreteTransfer->getSku())
            ->getIsActive();
    }

    /**
     * @param array $products
     *
     * @return array
     */
    protected function collectFilteredResults(array $products): array
    {
        $results = [];

        foreach ($products as $product) {
            $results[$product[static::KEY_FILTERED_PRODUCTS_RESULT]] = $product[static::KEY_FILTERED_PRODUCTS_PRODUCT_NAME];
        }

        return $results;
    }

    /**
     * @param string[] $skus
     *
     * @return array
     */
    public function getProductConcreteIdsByConcreteSkus(array $skus): array
    {
        $results = $this->getFactory()
            ->createProductQuery()
            ->filterBySku_In($skus)
            ->select([
                SpyProductTableMap::COL_ID_PRODUCT,
                SpyProductTableMap::COL_SKU,
            ])
            ->find()
            ->getData();

        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[$result[SpyProductTableMap::COL_SKU]] = $result[SpyProductTableMap::COL_ID_PRODUCT];
        }

        return $formattedResults;
    }

    /**
     * @param int[] $productIds
     *
     * @return array
     */
    public function getProductConcreteSkusByConcreteIds(array $productIds): array
    {
        $results = $this->getFactory()
            ->createProductQuery()
            ->filterByIdProduct_In($productIds)
            ->select([
                SpyProductTableMap::COL_ID_PRODUCT,
                SpyProductTableMap::COL_SKU,
            ])
            ->find()
            ->getData();

        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[$result[SpyProductTableMap::COL_SKU]] = $result[SpyProductTableMap::COL_ID_PRODUCT];
        }

        return $formattedResults;
    }

    /**
     * @module Locale
     * @module Store
     *
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductConcreteTransfersByProductIds(array $productIds): array
    {
        if (empty($productIds)) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductQuery()
            ->filterByIdProduct_In($productIds)
            ->joinWithSpyProductAbstract()
            ->joinWithSpyProductLocalizedAttributes()
            ->useSpyProductLocalizedAttributesQuery()
                ->joinWithLocale()
            ->endUse()
            ->useSpyProductAbstractQuery()
                ->joinWithSpyProductAbstractStore()
                ->useSpyProductAbstractStoreQuery()
                    ->joinWithSpyStore()
                ->endUse()
            ->endUse();

        $productConcreteEntities = $query->find();

        return $this->getProductConcreteTransfersMappedFromProductConcreteEntities($productConcreteEntities);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductConcreteTransfersByProductAbstractIds(array $productAbstractIds): array
    {
        if (empty($productAbstractIds)) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);

        $productConcreteEntities = $query->find();

        return $this->getProductConcreteTransfersMappedFromProductConcreteEntities($productConcreteEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productConcreteEntities
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getProductConcreteTransfersMappedFromProductConcreteEntities(ObjectCollection $productConcreteEntities): array
    {
        $productConcreteTransfers = [];
        $productMapper = $this->getFactory()->createProductMapper();

        foreach ($productConcreteEntities as $productConcreteEntity) {
            $productConcreteTransfers[] = $productMapper->mapProductConcreteEntityToTransfer(
                $productConcreteEntity,
                new ProductConcreteTransfer()
            );
        }

        return $productConcreteTransfers;
    }
}
