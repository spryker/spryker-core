<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
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
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Util\PropelModelPager;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Product\Persistence\ProductPersistenceFactory getFactory()
 */
class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    /**
     * @var string
     */
    public const KEY_FILTERED_PRODUCTS_RESULT = 'result';

    /**
     * @var string
     */
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
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductEntityTransfer>
     */
    public function findProductConcreteByIds(array $productIds): array
    {
        $productQuery = $this->getFactory()
            ->createProductQuery()
            ->joinWithSpyProductAbstract()
            ->filterByIdProduct_In($productIds);

        return $this->buildQueryFromCriteria($productQuery)->find();
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
            Criteria::LIKE,
        );

        $productAbstractQuery = $this->getFactory()
            ->createProductAbstractQuery();
        $productAbstractQuery->leftJoinSpyProductAbstractLocalizedAttributes()
            ->addJoinCondition(
                'SpyProductAbstractLocalizedAttributes',
                sprintf('SpyProductAbstractLocalizedAttributes.fk_locale = %d', $localeTransfer->getIdLocale()),
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

        /** @var \Propel\Runtime\Collection\ObjectCollection $abstractProducts */
        $abstractProducts = $productAbstractQuery->find();

        return $this->collectFilteredResults(
            $abstractProducts->toArray(),
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
            Criteria::LIKE,
        );

        $productConcreteQuery = $this->getFactory()
            ->createProductQuery();
        $productConcreteQuery->leftJoinSpyProductLocalizedAttributes()
            ->addJoinCondition(
                'SpyProductLocalizedAttributes',
                sprintf('SpyProductLocalizedAttributes.fk_locale = %d', $localeTransfer->getIdLocale()),
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

        /** @var \Propel\Runtime\Collection\ObjectCollection $concreteProducts */
        $concreteProducts = $productConcreteQuery->find();

        return $this->collectFilteredResults(
            $concreteProducts->toArray(),
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
     * @param array<int> $productConcreteIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
        $productQuery = $this->getFactory()
            ->createProductQuery()
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductTableMap::COL_ID_PRODUCT]);

        /** @var \Propel\Runtime\Collection\ObjectCollection $products */
        $products = $productQuery
            ->filterByIdProduct_In($productConcreteIds)
            ->find();

        return $products->toKeyValue(SpyProductTableMap::COL_ID_PRODUCT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
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
     * @param array<int> $productAbstractIds
     *
     * @return array<int>
     */
    public function findProductConcreteIdsByProductAbstractIds(array $productAbstractIds): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productConcreteIds */
        $productConcreteIds = $this->getFactory()
            ->createProductQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->select([SpyProductTableMap::COL_ID_PRODUCT])
            ->find();

        return $productConcreteIds->toArray();
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
     * @param array<string> $skus
     *
     * @return array<int>
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
     * @param array<int> $productIds
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
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfersByProductIds(array $productIds): array
    {
        if (!$productIds) {
            return [];
        }

        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $query */
        $query = $this->getFactory()
            ->createProductQuery()
            ->filterByIdProduct_In($productIds)
            ->joinWithSpyProductAbstract()
            ->joinWithSpyProductLocalizedAttributes()
            ->useSpyProductLocalizedAttributesQuery()
                ->joinWithLocale()
            ->endUse();

        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $query */
        $query = $query
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
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfersByProductAbstractIds(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
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
            Criteria::LIKE,
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
                $this->getProductAbstractTransfersMappedFromProductAbstractEntities($productAbstractEntities),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcretesByFilter(FilterTransfer $filterTransfer): array
    {
        $productConcreteEntities = $this->buildQueryFromCriteria(
            $this->getFactory()->createProductQuery(),
            $filterTransfer,
        )->setFormatter(ModelCriteria::FORMAT_OBJECT)->find();

        return $this->getProductConcreteTransfersMappedFromProductConcreteEntities($productConcreteEntities);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\Util\PropelModelPager
     */
    protected function getPaginationModelFromQuery(
        SpyProductAbstractQuery $productAbstractQuery,
        PaginationTransfer $paginationTransfer
    ): PropelModelPager {
        $page = $paginationTransfer
            ->requirePage()
            ->getPage();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPage();

        return $productAbstractQuery->paginate($page, $maxPerPage);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $productConcreteEntities
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function getProductConcreteTransfersMappedFromProductConcreteEntities(Collection $productConcreteEntities): array
    {
        $productConcreteTransfers = [];
        $productMapper = $this->getFactory()->createProductMapper();

        foreach ($productConcreteEntities as $productConcreteEntity) {
            $productConcreteTransfers[] = $productMapper->mapProductConcreteEntityToTransfer(
                $productConcreteEntity,
                new ProductConcreteTransfer(),
            );
        }

        return $productConcreteTransfers;
    }

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcretesByConcreteSkus(array $productConcreteSkus): array
    {
        $productConcreteEntities = $this->getFactory()
            ->createProductQuery()
            ->joinWithSpyProductAbstract()
            ->joinWithSpyProductLocalizedAttributes()
            ->filterBySku_In($productConcreteSkus)
            ->useSpyProductLocalizedAttributesQuery()
                ->joinWithLocale()
            ->endUse()
            ->find();

        if ($productConcreteEntities->count() === 0) {
            return [];
        }

        return $this->mapProductEntitiesToProductConcreteTransfersWithoutStores($productConcreteEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $productEntities
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function mapProductEntitiesToProductConcreteTransfersWithoutStores(Collection $productEntities): array
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
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    protected function getProductAbstractTransfersMappedFromProductAbstractEntities(ObjectCollection $productAbstractEntities): ArrayObject
    {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfers */
        $productAbstractTransfers = new ArrayObject();
        $productMapper = $this->getFactory()->createProductMapper();

        foreach ($productAbstractEntities as $productAbstractEntity) {
            $productAbstractTransfers[] = $productMapper->mapProductAbstractEntityToProductAbstractTransferForSuggestion(
                $productAbstractEntity,
                new ProductAbstractTransfer(),
            );
        }

        return $productAbstractTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
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
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Product\Persistence\SpyProduct> $productConcreteEntities
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function mapProductConcreteEntitiesToProductConcreteTransfersWithoutRelations(
        ObjectCollection $productConcreteEntities
    ): array {
        $productConcreteTransfers = [];
        $productMapper = $this->getFactory()->createProductMapper();

        foreach ($productConcreteEntities as $productConcreteEntity) {
            $productConcreteTransfers[] = $productMapper->mapProductConcreteEntityToProductConcreteTransferWithoutRelations(
                $productConcreteEntity,
                new ProductConcreteTransfer(),
            );
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer $productUrlCriteriaFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\UrlTransfer>
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
     * @param array<string> $productAbstractSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getRawProductAbstractTransfersByAbstractSkus(array $productAbstractSkus): array
    {
        $productAbstractEntities = $this->getFactory()->createProductAbstractQuery()
            ->filterBySku_In($productAbstractSkus)
            ->find();

        return $this->mapProductAbstractEntitiesToProductAbstractTransfersWithoutRelations($productAbstractEntities);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getActiveProductAbstractsByProductAbstractIds(array $productAbstractIds): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Product\Persistence\SpyProductAbstract> $productAbstractEntities */
        $productAbstractEntities = $this->getFactory()
            ->createProductAbstractQuery()
            ->filterByIdProductAbstract_In($productAbstractIds)
            ->joinWithSpyProduct()
            ->useSpyProductQuery()
                ->filterByIsActive(true)
            ->endUse()
            ->find();

        return $this->mapProductAbstractEntitiesToProductAbstractTransfersWithoutRelations($productAbstractEntities);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getRawProductAbstractsByProductAbstractIds(array $productAbstractIds): array
    {
        $productAbstractEntities = $this->getFactory()->createProductAbstractQuery()
            ->filterByIdProductAbstract_In($productAbstractIds)
            ->find();

        return $this->mapProductAbstractEntitiesToProductAbstractTransfersWithoutRelations($productAbstractEntities);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, string>
     */
    public function getProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstract(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        $productAbstractLocalizedAttributesDataCollection = $this->getFactory()
            ->createProductAbstractLocalizedAttributesQuery()
            ->select([SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductAbstractLocalizedAttributesTableMap::COL_NAME])
            ->filterByName(null, Criteria::ISNOTNULL)
            ->filterByName('', Criteria::NOT_EQUAL)
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find();

        $productAbstractLocalizedAttributeNames = [];
        foreach ($productAbstractLocalizedAttributesDataCollection as $productAbstractLocalizedAttributesData) {
            /** @var int $idProductAbstract */
            $idProductAbstract = $productAbstractLocalizedAttributesData[SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT];
            $name = $productAbstractLocalizedAttributesData[SpyProductAbstractLocalizedAttributesTableMap::COL_NAME];

            if (!isset($productAbstractLocalizedAttributeNames[$idProductAbstract])) {
                $productAbstractLocalizedAttributeNames[$idProductAbstract] = $name;
            }
        }

        return $productAbstractLocalizedAttributeNames;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCriteriaTransfer $productCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
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

        if ($productCriteriaTransfer->getIdProductAbstract()) {
            $productQuery->filterByFkProductAbstract($productCriteriaTransfer->getIdProductAbstract());
        }

        if (count($productCriteriaTransfer->getAttributes()) > 0) {
            $criteria = new Criteria();
            foreach ($productCriteriaTransfer->getAttributes() as $key => $value) {
                $attributesLikeCriteria = $criteria->getNewCriterion(
                    SpyProductTableMap::COL_ATTRIBUTES,
                    sprintf('%%"%s":"%s"%%', $key, $value),
                    Criteria::LIKE,
                );
                $productQuery->addAnd($attributesLikeCriteria);
            }
        }

        return $productQuery;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Product\Persistence\SpyProductAbstract> $productAbstractEntities
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    protected function mapProductAbstractEntitiesToProductAbstractTransfersWithoutRelations(Collection $productAbstractEntities): array
    {
        $productAbstractTransfers = [];
        $mapper = $this->getFactory()->createProductMapper();

        foreach ($productAbstractEntities as $productAbstractEntity) {
            $productAbstractTransfers[] = $mapper->mapProductAbstractEntityToProductAbstractTransferWithoutRelations(
                $productAbstractEntity,
                new ProductAbstractTransfer(),
            );
        }

        return $productAbstractTransfers;
    }

    /**
     * Result format:
     * [
     *     $idProduct => [LocalizedAttributesTransfer, ...],
     *     ...
     * ]
     *
     * @param array<int> $productIds
     *
     * @return array<int, array<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>>
     */
    public function getLocalizedAttributesGroupedByIdProduct(array $productIds): array
    {
        $productLocalizedAttributesCollection = $this->getFactory()->createProductLocalizedAttributesQuery()
            ->filterByFkProduct_In($productIds)
            ->joinWithLocale()
            ->find();

        $result = [];

        $localizedAttributesMapper = $this->getFactory()->createLocalizedAttributesMapper();

        /** @var \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes $productLocalizedAttributesEntity */
        foreach ($productLocalizedAttributesCollection as $productLocalizedAttributesEntity) {
            $result[$productLocalizedAttributesEntity->getFkProduct()][] = $localizedAttributesMapper->mapProductLocalizedAttributesEntityToTransfer(
                $productLocalizedAttributesEntity,
                new LocalizedAttributesTransfer(),
            );
        }

        return $result;
    }

    /**
     * @param int $productExportPublishChunkSize
     * @param int $lastProductId
     * @param int|null $idStore Deprecated: Will be removed without replacement.
     *
     * @return array<int>
     */
    public function getAllProductConcreteIdsWithLimit(
        int $productExportPublishChunkSize,
        int $lastProductId,
        ?int $idStore = null
    ): array {
        $productQuery = $this->getFactory()->createProductQuery()
            ->select(SpyProductTableMap::COL_ID_PRODUCT)
            ->where(SpyProductTableMap::COL_ID_PRODUCT . ' > ?', $lastProductId)
            ->limit($productExportPublishChunkSize)
            ->orderBy(SpyProductTableMap::COL_ID_PRODUCT);

        if ($idStore) {
            $productQuery->useSpyProductAbstractQuery()
                ->useSpyProductAbstractStoreQuery()
                    ->filterByFkStore($idStore)
                ->endUse()
            ->endUse();
        }

        /** @var \Propel\Runtime\Collection\ArrayCollection $products */
        $products = $productQuery->find();

        return $products->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function getProductAbstractCollection(ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer): ProductAbstractCollectionTransfer
    {
        $productAbstractQuery = $this->getFactory()->createProductAbstractQuery();

        $productAbstractQuery = $this->applyProductAbstractCriteria($productAbstractCriteriaTransfer, $productAbstractQuery);
        $productAbstractQuery = $this->applyProductAbstractSortings($productAbstractCriteriaTransfer, $productAbstractQuery);
        $productAbstractQuery = $this->applyProductAbstractPagination($productAbstractCriteriaTransfer, $productAbstractQuery);

        $productAbstractCollectionTransfer = (new ProductAbstractCollectionTransfer())
            ->setPagination($productAbstractCriteriaTransfer->getPagination());

        $productAbstractCollection = $productAbstractQuery->find();

        return $this->getFactory()->createProductMapper()
            ->mapProductAbstractEntitiesToProductAbstractCollectionTransfer(
                $productAbstractCollection,
                $productAbstractCollectionTransfer,
            );
    }

    /**
     * @param array<int, int> $productAbstractIds
     *
     * @return array<int, \Generated\Shared\Transfer\StoreRelationTransfer>
     */
    public function getProductAbstractStoreRelations(array $productAbstractIds): array
    {
        $productAbstractStoreQuery = $this->getFactory()
            ->createProductAbstractStoreQuery();
        $productAbstractStoreEntities = $productAbstractStoreQuery
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->leftJoinWithSpyStore()
            ->find();

        return $this->getFactory()
            ->createProductAbstractStoreMapper()
            ->mapProductAbstractStoreEntitiesToStoreRelationTransfers($productAbstractStoreEntities);
    }

    /**
     * @param array<int, int> $productAbstractIds
     *
     * @return array<int, array<\Generated\Shared\Transfer\LocalizedAttributesTransfer>>
     */
    public function getProductAbstractLocalizedAttributes(array $productAbstractIds): array
    {
        $productAbstractLocalizedAttributesEntities = $this->getFactory()->createProductAbstractLocalizedAttributesQuery()
            ->joinWithLocale()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find();

        return $this->getFactory()->createLocalizedAttributesMapper()
            ->mapProductLocalizedAttributesEntitiesToLocalizedAttributesTransfers($productAbstractLocalizedAttributesEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function applyProductAbstractCriteria(
        ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer,
        SpyProductAbstractQuery $productAbstractQuery
    ): SpyProductAbstractQuery {
        if ($productAbstractCriteriaTransfer->getProductAbstractConditions()) {
            if ($productAbstractCriteriaTransfer->getProductAbstractConditions()->getSkus()) {
                $productAbstractQuery->filterBySku_In($productAbstractCriteriaTransfer->getProductAbstractConditions()->getSkus());
            }
            if ($productAbstractCriteriaTransfer->getProductAbstractConditions()->getIds()) {
                $productAbstractQuery->filterByIdProductAbstract_In($productAbstractCriteriaTransfer->getProductAbstractConditions()->getIds());
            }
        }

        return $productAbstractQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function applyProductAbstractSortings(
        ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer,
        SpyProductAbstractQuery $productAbstractQuery
    ): SpyProductAbstractQuery {
        foreach ($productAbstractCriteriaTransfer->getSortCollection() as $sortTransfer) {
            $productAbstractQuery->orderBy(
                $sortTransfer->getField(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $productAbstractQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function applyProductAbstractPagination(
        ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer,
        SpyProductAbstractQuery $productAbstractQuery
    ): SpyProductAbstractQuery {
        if ($productAbstractCriteriaTransfer->getPagination()) {
            $productAbstractCriteriaTransfer->getPagination()->setNbResults($productAbstractQuery->count());

            $productAbstractQuery
                ->setLimit($productAbstractCriteriaTransfer->getPagination()->getLimit())
                ->setOffset($productAbstractCriteriaTransfer->getPagination()->getOffset());
        }

        return $productAbstractQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductConcreteCollection(
        ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
    ): ProductConcreteCollectionTransfer {
        $productConcreteCollectionTransfer = new ProductConcreteCollectionTransfer();

        $productConcreteQuery = $this->getFactory()->createProductQuery();
        $productConcreteQuery = $this->applyProductConcreteFilters($productConcreteQuery, $productConcreteCriteriaTransfer);

        $paginationTransfer = $productConcreteCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery */
            $productConcreteQuery = $this->applyPaginationToQuery($productConcreteQuery, $paginationTransfer);
            $productConcreteCollectionTransfer->setPagination($paginationTransfer);
        }

        $productConcreteQuery = $this->expandProductConcreteQueryWithProductLocalizedAttributes($productConcreteQuery, $productConcreteCriteriaTransfer);
        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery */
        $productConcreteQuery = $this->applySortingToQuery(
            $productConcreteQuery,
            $productConcreteCriteriaTransfer->getSortCollection(),
        );

        return $this->getFactory()
            ->createProductMapper()
            ->mapProductEntitiesToProductConcreteCollection(
                $productConcreteQuery->find(),
                $productConcreteCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeyCriteriaTransfer $productAttributeKeyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer
     */
    public function getProductAttributeKeyCollection(
        ProductAttributeKeyCriteriaTransfer $productAttributeKeyCriteriaTransfer
    ): ProductAttributeKeyCollectionTransfer {
        $productAttributeKeyCollectionTransfer = new ProductAttributeKeyCollectionTransfer();

        $productAttributeKeyQuery = $this->getFactory()->createProductAttributeKeyQuery();
        $productAttributeKeyQuery = $this->applyProductAttributeKeyFilters($productAttributeKeyQuery, $productAttributeKeyCriteriaTransfer);

        $paginationTransfer = $productAttributeKeyCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            /** @var \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery $productAttributeKeyQuery */
            $productAttributeKeyQuery = $this->applyPaginationToQuery($productAttributeKeyQuery, $paginationTransfer);
            $productAttributeKeyCollectionTransfer->setPagination($paginationTransfer);
        }

        /** @var \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery $productAttributeKeyQuery */
        $productAttributeKeyQuery = $this->applySortingToQuery(
            $productAttributeKeyQuery,
            $productAttributeKeyCriteriaTransfer->getSortCollection(),
        );

        return $this->getFactory()
            ->createProductAttributeKeyMapper()
            ->mapProductAttributeKeyEntitiesToProductAttributeKeyCollection(
                $productAttributeKeyQuery->find(),
                $productAttributeKeyCollectionTransfer,
            );
    }

    /**
     * @module Locale
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function expandProductConcreteQueryWithProductLocalizedAttributes(
        SpyProductQuery $productConcreteQuery,
        ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
    ): SpyProductQuery {
        if (!$productConcreteQuery->count()) {
            return $productConcreteQuery;
        }

        /** @var \Propel\Runtime\Collection\ArrayCollection $productConcreteIds */
        $productConcreteIds = $productConcreteQuery
            ->groupByIdProduct()
            ->select([SpyProductTableMap::COL_ID_PRODUCT])
            ->find();

        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery */
        $productConcreteQuery = $this->getFactory()
            ->createProductQuery()
            ->filterByIdProduct_In($productConcreteIds->toArray())
            ->joinWithSpyProductAbstract()
            ->joinWithSpyProductLocalizedAttributes()
            ->useSpyProductLocalizedAttributesQuery()
                ->joinWithLocale()
            ->endUse();

        $productConcreteConditionsTransfer = $productConcreteCriteriaTransfer->getProductConcreteConditions();

        if ($productConcreteConditionsTransfer && $productConcreteConditionsTransfer->getLocaleNames()) {
            $productConcreteQuery = $productConcreteQuery
                ->useSpyProductLocalizedAttributesQuery()
                    ->useLocaleQuery()
                        ->filterByLocaleName_In($productConcreteConditionsTransfer->getLocaleNames())
                    ->endUse()
                ->endUse();
        }

        /** @phpstan-var \Orm\Zed\Product\Persistence\SpyProductQuery */
        return $productConcreteQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function applyProductConcreteFilters(
        SpyProductQuery $productConcreteQuery,
        ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
    ): SpyProductQuery {
        $productConcreteConditionsTransfer = $productConcreteCriteriaTransfer->getProductConcreteConditions();
        if ($productConcreteConditionsTransfer === null) {
            return $productConcreteQuery;
        }

        if ($productConcreteConditionsTransfer->getSkus()) {
            $productConcreteQuery->filterBySku_In($productConcreteConditionsTransfer->getSkus());
        }

        if ($productConcreteConditionsTransfer->getLocaleNames()) {
            $productConcreteQuery = $this->filterProductConcretesByLocaleName(
                $productConcreteQuery,
                $productConcreteConditionsTransfer->getLocaleNames(),
            );
        }

        return $productConcreteQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyPaginationToQuery(
        ModelCriteria $query,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($query->count());

            $query->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $query;
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage()) {
            $paginationModel = $query->paginate(
                $paginationTransfer->getPage(),
                $paginationTransfer->getMaxPerPage(),
            );

            $paginationTransfer->setNbResults($paginationModel->getNbResults())
                ->setFirstIndex($paginationModel->getFirstIndex())
                ->setLastIndex($paginationModel->getLastIndex())
                ->setFirstPage($paginationModel->getFirstPage())
                ->setLastPage($paginationModel->getLastPage())
                ->setNextPage($paginationModel->getNextPage())
                ->setPreviousPage($paginationModel->getPreviousPage());

            return $paginationModel->getQuery();
        }

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortCollection
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applySortingToQuery(
        ModelCriteria $query,
        ArrayObject $sortCollection
    ): ModelCriteria {
        foreach ($sortCollection as $sortTransfer) {
            $query->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $query;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery $productAttributeKeyQuery
     * @param \Generated\Shared\Transfer\ProductAttributeKeyCriteriaTransfer $productAttributeKeyCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    protected function applyProductAttributeKeyFilters(
        SpyProductAttributeKeyQuery $productAttributeKeyQuery,
        ProductAttributeKeyCriteriaTransfer $productAttributeKeyCriteriaTransfer
    ): SpyProductAttributeKeyQuery {
        $productAttributeKeyConditionsTransfer = $productAttributeKeyCriteriaTransfer->getProductAttributeKeyConditions();
        if ($productAttributeKeyConditionsTransfer === null) {
            return $productAttributeKeyQuery;
        }

        if ($productAttributeKeyConditionsTransfer->getKeys() !== []) {
            $productAttributeKeyQuery->filterByKey_In($productAttributeKeyConditionsTransfer->getKeys());
        }

        if ($productAttributeKeyConditionsTransfer->getIsSuper() !== null) {
            $productAttributeKeyQuery->filterByIsSuper($productAttributeKeyConditionsTransfer->getIsSuperOrFail());
        }

        return $productAttributeKeyQuery;
    }

    /**
     * Provided query can not contain group by, offset, or limit directives otherwise it will alter the results.
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param list<string> $localeNames
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function filterProductConcretesByLocaleName(SpyProductQuery $productConcreteQuery, array $localeNames): SpyProductQuery
    {
        $localizedProductConcreteQuery = clone $productConcreteQuery;

        /** @var \Propel\Runtime\Collection\ArrayCollection $hiddenProductConcreteIds */
        $hiddenProductConcreteIds = $localizedProductConcreteQuery
            ->groupByIdProduct()
            ->joinWithSpyProductLocalizedAttributes()
            ->useSpyProductLocalizedAttributesQuery()
                ->joinWithLocale()
                ->useLocaleQuery()
                    ->filterByLocaleName_In($localeNames)
                ->endUse()
            ->endUse()
            ->select([SpyProductTableMap::COL_ID_PRODUCT])
            ->find();

        if (!$hiddenProductConcreteIds->toArray()) {
            return $productConcreteQuery->filterByIdProduct();
        }

        return $productConcreteQuery->filterByIdProduct_In($hiddenProductConcreteIds->toArray());
    }
}
