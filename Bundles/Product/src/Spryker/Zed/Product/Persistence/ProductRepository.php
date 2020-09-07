<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Util\PropelModelPager;
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
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
        $productQuery = $this->getFactory()
            ->createProductQuery()
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductTableMap::COL_ID_PRODUCT]);

        return $productQuery
            ->filterByIdProduct_In($productConcreteIds)
            ->find()
            ->toKeyValue(SpyProductTableMap::COL_ID_PRODUCT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT);
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
        /** @var \Propel\Runtime\Collection\ObjectCollection|null $productConcreteIds */
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
     * @return int[]
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
                ->joinSpyProductAbstractStore()
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
     * @param string $search
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer
     */
    public function getProductAbstractSuggestionCollectionBySkuOrLocalizedName(
        string $search,
        PaginationTransfer $paginationTransfer,
        LocaleTransfer $localeTransfer
    ): ProductAbstractSuggestionCollectionTransfer {
        $criteria = new Criteria();
        $skuLikeCriteria = $criteria->getNewCriterion(
            SpyProductAbstractTableMap::COL_SKU,
            '%' . $search . '%',
            Criteria::LIKE
        );

        $productAbstractQuery = $this->getFactory()
            ->createProductAbstractQuery();
        $productAbstractQuery->leftJoinSpyProductAbstractLocalizedAttributes()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($localeTransfer->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::KEY_FILTERED_PRODUCTS_PRODUCT_NAME)
            ->where('lower(' . SpyProductAbstractLocalizedAttributesTableMap::COL_NAME . ') like ?', '%' . mb_strtolower($search) . '%')
            ->addOr($skuLikeCriteria)
            ->addAscendingOrderByColumn(SpyProductAbstractTableMap::COL_SKU);

        $paginationModel = $this->getPaginationModelFromQuery($productAbstractQuery, $paginationTransfer);
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $productAbstractQuery = $paginationModel->getQuery();

        $productAbstractEntities = $productAbstractQuery->find();

        return (new ProductAbstractSuggestionCollectionTransfer())
            ->setPagination($paginationTransfer)
            ->setProductAbstracts(
                $this->getProductAbstractTransfersMappedFromProductAbstractEntities($productAbstractEntities)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductConcretesByFilter(FilterTransfer $filterTransfer): array
    {
        $productConcreteEntities = $this->buildQueryFromCriteria(
            $this->getFactory()->createProductQuery(),
            $filterTransfer
        )->setFormatter(ModelCriteria::FORMAT_OBJECT)->find();

        return $this->getProductConcreteTransfersMappedFromProductConcreteEntities($productConcreteEntities);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $spyProductAbstractQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\Util\PropelModelPager
     */
    protected function getPaginationModelFromQuery(
        SpyProductAbstractQuery $spyProductAbstractQuery,
        PaginationTransfer $paginationTransfer
    ): PropelModelPager {
        $page = $paginationTransfer
            ->requirePage()
            ->getPage();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPage();

        return $spyProductAbstractQuery->paginate($page, $maxPerPage);
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

    /**
     * @param string[] $productConcreteSkus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductConcretesByConcreteSkus(array $productConcreteSkus): array
    {
        $productConcreteEntities = $this->getFactory()
            ->createProductQuery()
            ->joinWithSpyProductAbstract()
            ->joinWithSpyProductLocalizedAttributes()
            ->filterBySku_In($productConcreteSkus)
            ->find();

        if ($productConcreteEntities->count() === 0) {
            return [];
        }

        return $this->mapProductEntitiesToProductConcreteTransfersWithoutStores($productConcreteEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productEntities
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function mapProductEntitiesToProductConcreteTransfersWithoutStores(ObjectCollection $productEntities): array
    {
        $productConcreteTransfers = [];
        $productMapper = $this->getFactory()->createProductMapper();

        foreach ($productEntities as $productEntity) {
            $productConcreteTransfers[] = $productMapper
                ->mapProductEntityToProductConcreteTransferWithoutStores($productEntity, new ProductConcreteTransfer());
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productAbstractEntities
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    protected function getProductAbstractTransfersMappedFromProductAbstractEntities(ObjectCollection $productAbstractEntities): ArrayObject
    {
        $productAbstractTransfers = new ArrayObject();
        $productMapper = $this->getFactory()->createProductMapper();

        foreach ($productAbstractEntities as $productAbstractEntity) {
            $productAbstractTransfers[] = $productMapper->mapProductAbstractEntityToProductAbstractTransferForSuggestion(
                $productAbstractEntity,
                new ProductAbstractTransfer()
            );
        }

        return $productAbstractTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getRawProductConcreteTransfersByFilter(FilterTransfer $filterTransfer): array
    {
        $productQuery = $this->getFactory()->createProductQuery();
        $productConcreteEntities = $this->buildQueryFromCriteria($productQuery, $filterTransfer)
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find();

        return $this->mapProductConcreteEntitiesToProductConcreteTransfersWithoutRelations($productConcreteEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Product\Persistence\SpyProduct[] $productConcreteEntities
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function mapProductConcreteEntitiesToProductConcreteTransfersWithoutRelations(
        ObjectCollection $productConcreteEntities
    ): array {
        $productConcreteTransfers = [];
        $productMapper = $this->getFactory()->createProductMapper();

        foreach ($productConcreteEntities as $productConcreteEntity) {
            $productConcreteTransfers[] = $productMapper->mapProductConcreteEntityToProductConcreteTransferWithoutRelations(
                $productConcreteEntity,
                new ProductConcreteTransfer()
            );
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer $productUrlCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    public function getProductUrls(ProductUrlCriteriaFilterTransfer $productUrlCriteriaFilterTransfer): array
    {
        $urlQuery = $this->getFactory()
            ->getUrlQueryContainer()
            ->queryUrls();

        $urlQuery = $this->setUrlFilters($urlQuery, $productUrlCriteriaFilterTransfer);
        $urlEntities = $urlQuery->find();

        $urlTransfers = [];

        foreach ($urlEntities as $urlEntity) {
            $urlTransfers[] = (new UrlTransfer())->fromArray($urlEntity->toArray(), true);
        }

        return $urlTransfers;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrlQuery $urlQuery
     * @param \Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer $productUrlCriteriaFilterTransfer
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected function setUrlFilters(
        SpyUrlQuery $urlQuery,
        ProductUrlCriteriaFilterTransfer $productUrlCriteriaFilterTransfer
    ): SpyUrlQuery {
        if (count($productUrlCriteriaFilterTransfer->getProductAbstractIds())) {
            $urlQuery->filterByFkResourceProductAbstract_In($productUrlCriteriaFilterTransfer->getProductAbstractIds());
        }

        if ($productUrlCriteriaFilterTransfer->getIdLocale()) {
            $urlQuery->filterByFkLocale($productUrlCriteriaFilterTransfer->getIdLocale());
        }

        return $urlQuery;
    }

    /**
     * @param string[] $productAbstractSkus
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getRawProductAbstractTransfersByAbstractSkus(array $productAbstractSkus): array
    {
        $productAbstractEntities = $this->getFactory()->createProductAbstractQuery()
            ->filterBySku_In($productAbstractSkus)
            ->find();

        return $this->mapProductAbstractEntitiesToProductAbstractTransfersWithoutRelations($productAbstractEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCriteriaTransfer $productCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductConcretesByCriteria(ProductCriteriaTransfer $productCriteriaTransfer): array
    {
        $productQuery = $this->getFactory()
            ->createProductQuery()
            ->joinWithSpyProductAbstract()
            ->joinWithSpyProductLocalizedAttributes();

        $productQuery = $this->applyCriteriaFilter($productQuery, $productCriteriaTransfer);
        $productConcreteEntities = $productQuery->find();

        return $this->mapProductEntitiesToProductConcreteTransfersWithoutStores($productConcreteEntities);
    }

    /**
     * @module Store
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery
     * @param \Generated\Shared\Transfer\ProductCriteriaTransfer $productCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function applyCriteriaFilter(SpyProductQuery $productQuery, ProductCriteriaTransfer $productCriteriaTransfer): SpyProductQuery
    {
        if ($productCriteriaTransfer->getSkus()) {
            $productQuery->filterBySku_In($productCriteriaTransfer->getSkus());
        }
        if ($productCriteriaTransfer->getIsActive() !== null) {
            $productQuery->filterByIsActive($productCriteriaTransfer->getIsActive());
        }
        if ($productCriteriaTransfer->getIdStore()) {
            $productQuery->useSpyProductAbstractQuery()
                ->useSpyProductAbstractStoreQuery()
                    ->filterByFkStore($productCriteriaTransfer->getIdStore())
                ->endUse()
            ->endUse();
        }

        return $productQuery;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Product\Persistence\SpyProductAbstract[] $productAbstractEntities
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    protected function mapProductAbstractEntitiesToProductAbstractTransfersWithoutRelations(ObjectCollection $productAbstractEntities): array
    {
        $productAbstractTransfers = [];
        $mapper = $this->getFactory()->createProductMapper();

        foreach ($productAbstractEntities as $productAbstractEntity) {
            $productAbstractTransfers[] = $mapper->mapProductAbstractEntityToProductAbstractTransferWithoutRelations(
                $productAbstractEntity,
                new ProductAbstractTransfer()
            );
        }

        return $productAbstractTransfers;
    }
}
