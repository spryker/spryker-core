<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractStoreTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTableMap;
use Orm\Zed\ProductValidity\Persistence\Map\SpyProductValidityTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\LikeCriterion;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel\ProductAbstractTableDataMapper;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel\ProductTableDataMapper;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiPersistenceFactory getFactory()
 */
class ProductMerchantPortalGuiRepository extends AbstractRepository implements ProductMerchantPortalGuiRepositoryInterface
{
    /**
     * @var string
     */
    protected const SUFFIX_PRICE_TYPE_NET = '_net';

    /**
     * @var string
     */
    protected const SUFFIX_PRICE_TYPE_GROSS = '_gross';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider::COL_KEY_SKU
     *
     * @var string
     */
    protected const COL_KEY_PRODUCT_ABSTRACT_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_SKU
     *
     * @var string
     */
    protected const COL_KEY_PRODUCT_SKU = 'sku';

    /**
     * @var string
     */
    protected const COL_NAME_FALLBACK = 'name_fallback';

    /**
     * @var string
     */
    protected const RELATION_LOCALE_FALLBACK = 'locale_fallback';

    /**
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function getProductAbstractTableData(
        MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
    ): ProductAbstractCollectionTransfer {
        $merchantProductAbstractPropelQuery = $this->buildProductAbstractTableBaseQuery($merchantProductTableCriteriaTransfer);
        $merchantProductAbstractPropelQuery = $this->applyProductAbstractSearch(
            $merchantProductAbstractPropelQuery,
            $merchantProductTableCriteriaTransfer,
        );
        $merchantProductAbstractPropelQuery = $this->addProductAbstractSorting(
            $merchantProductAbstractPropelQuery,
            $merchantProductTableCriteriaTransfer,
        );
        $merchantProductAbstractPropelQuery = $this->addProductAbstractFilters(
            $merchantProductAbstractPropelQuery,
            $merchantProductTableCriteriaTransfer,
        );

        $propelPager = $merchantProductAbstractPropelQuery->paginate(
            $merchantProductTableCriteriaTransfer->getPageOrFail(),
            $merchantProductTableCriteriaTransfer->getPageSizeOrFail(),
        );

        $paginationTransfer = $this->getFactory()->createPropelModelPagerMapper()->mapPropelModelPagerToPaginationTransfer(
            $propelPager,
            new PaginationTransfer(),
        );
        $productAbstractCollectionTransfer = $this->getFactory()
            ->createProductAbstractTableDataMapper()
            ->mapProductAbstractTableDataArrayToProductAbstractCollectionTransfer(
                $propelPager->getResults()->getData(),
                new ProductAbstractCollectionTransfer(),
            );
        $productAbstractCollectionTransfer->setPagination($paginationTransfer);

        return $productAbstractCollectionTransfer;
    }

    /**
     * @module MerchantProduct
     * @module Product
     * @module ProductImage
     * @module Store
     * @module ProductCategory
     * @module Category
     *
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     */
    protected function buildProductAbstractTableBaseQuery(
        MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
    ): SpyMerchantProductAbstractQuery {
        $merchantProductAbstractPropelQuery = $this->getFactory()->getMerchantProductAbstractPropelQuery();
        $idLocale = $merchantProductTableCriteriaTransfer->getLocaleOrFail()->getIdLocaleOrFail();
        $idMerchant = $merchantProductTableCriteriaTransfer->getIdMerchantOrFail();

        $merchantProductAbstractPropelQuery->filterByFkMerchant($idMerchant)
            ->distinct()
            ->joinProductAbstract()
            ->useProductAbstractQuery()
                ->joinSpyProductAbstractLocalizedAttributes()
                ->useSpyProductAbstractLocalizedAttributesQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->leftJoinSpyProductAbstractLocalizedAttributes(static::RELATION_LOCALE_FALLBACK)
                ->addJoinCondition(
                    static::RELATION_LOCALE_FALLBACK,
                    sprintf('(%s is null OR %s = \'\')', SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, SpyProductAbstractLocalizedAttributesTableMap::COL_NAME),
                )
                ->addJoinCondition(static::RELATION_LOCALE_FALLBACK, static::RELATION_LOCALE_FALLBACK . '.name is not null')
                ->addJoinCondition(static::RELATION_LOCALE_FALLBACK, static::RELATION_LOCALE_FALLBACK . '.name != \'\'')
            ->endUse()
            ->select([
                ProductAbstractTransfer::ID_PRODUCT_ABSTRACT,
                ProductAbstractTransfer::SKU,
                ProductImageTransfer::EXTERNAL_URL_SMALL,
                ProductAbstractTransfer::NAME,
                ProductAbstractTransfer::ATTRIBUTES,
                ProductAbstractTransfer::CONCRETE_PRODUCT_COUNT,
                ProductAbstractTransfer::CATEGORY_NAMES,
                ProductAbstractTransfer::STORE_NAMES,
                ProductAbstractTransfer::IS_ACTIVE,
                ProductAbstractTransfer::APPROVAL_STATUS,
            ]);

        $merchantProductAbstractPropelQuery->addAsColumn(ProductAbstractTransfer::ID_PRODUCT_ABSTRACT, SpyMerchantProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->addAsColumn(ProductAbstractTransfer::SKU, SpyProductAbstractTableMap::COL_SKU)
            ->addAsColumn(ProductImageTransfer::EXTERNAL_URL_SMALL, sprintf('(%s)', $this->createProductImagesSubquery($idLocale)))
            ->addAsColumn(ProductAbstractTransfer::NAME, SpyProductAbstractLocalizedAttributesTableMap::COL_NAME)
            ->addAsColumn(ProductAbstractTransfer::ATTRIBUTES, sprintf('(%s)', $this->createProductAttributesSubquery()))
            ->addAsColumn(ProductAbstractTransfer::CONCRETE_PRODUCT_COUNT, sprintf('(%s)', $this->createProductsCountSubquery()))
            ->addAsColumn(ProductAbstractTransfer::CATEGORY_NAMES, sprintf('(%s)', $this->createProductAbstractCategoriesSubquery($idLocale)))
            ->addAsColumn(ProductAbstractTransfer::STORE_NAMES, sprintf('(%s)', $this->createProductAbstractStoresSubquery()))
            ->addAsColumn(ProductAbstractTransfer::IS_ACTIVE, sprintf('(%s) > 0', $this->createActiveProductsCountSubquery()))
            ->addAsColumn(ProductAbstractTransfer::APPROVAL_STATUS, SpyProductAbstractTableMap::COL_APPROVAL_STATUS)
            ->withColumn(static::RELATION_LOCALE_FALLBACK . '.name', static::COL_NAME_FALLBACK);

        return $merchantProductAbstractPropelQuery;
    }

    /**
     * @param int $idLocale
     *
     * @return string
     */
    protected function createProductImagesSubquery(int $idLocale): string
    {
        /** @var literal-string $where */
        $where = sprintf(
            '%1$s = %2$s AND (%3$s = %4$d OR %3$s IS NULL)',
            SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT,
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductImageSetTableMap::COL_FK_LOCALE,
            $idLocale,
        );
        $productImagesSubquery = $this->getFactory()->getProductImagePropelQuery()
            ->joinSpyProductImageSetToProductImage()
            ->useSpyProductImageSetToProductImageQuery()
            ->joinSpyProductImageSet()
            ->endUse()
            ->where($where)
            ->orderBy(SpyProductImageSetTableMap::COL_FK_LOCALE)
            ->limit(1);
        $productImagesSubquery->addSelectColumn(SpyProductImageTableMap::COL_EXTERNAL_URL_SMALL);

        $params = [];

        return $productImagesSubquery->createSelectSql($params);
    }

    /**
     * @return string
     */
    protected function createProductsCountSubquery(): string
    {
        $params = [];
        $productQuery = $this->getFactory()->getProductConcretePropelQuery();
        $productQuery->addAsColumn('products_count', 'COUNT(*)');

        /** @var literal-string $where */
        $where = SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT . ' = ' . SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT;
        $productQuery->where($where);

        return $productQuery->createSelectSql($params);
    }

    /**
     * @return string
     */
    protected function createActiveProductsCountSubquery(): string
    {
        $params = [];
        $productQuery = $this->getFactory()->getProductConcretePropelQuery();
        $productQuery->addAsColumn('products_count', 'COUNT(*)');

        /** @var literal-string $where */
        $where = SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT . ' = ' . SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT
            . ' AND ' . SpyProductTableMap::COL_IS_ACTIVE;
        $productQuery->where($where);

        return $productQuery->createSelectSql($params);
    }

    /**
     * @return string
     */
    protected function createProductAbstractStoresSubquery(): string
    {
        $productStoresSubquery = $this->getFactory()->getStorePropelQuery()
            ->joinSpyProductAbstractStore()
            ->where(sprintf('%s = %s', SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT));
        $productStoresSubquery->addAsColumn('stores', sprintf('GROUP_CONCAT(DISTINCT %s)', SpyStoreTableMap::COL_NAME));
        $params = [];

        return $productStoresSubquery->createSelectSql($params);
    }

    /**
     * @param int $idLocale
     *
     * @return string
     */
    protected function createProductAbstractCategoriesSubquery(int $idLocale): string
    {
        /** @var \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery $productStoresSubquery */
        $productStoresSubquery = $this->getFactory()->getProductCategoryPropelQuery()
            ->joinSpyCategory()
            ->useSpyCategoryQuery()
                ->joinAttribute()
            ->endUse();

        /** @var literal-string $where */
        $where = sprintf(
            '%s = %s AND %s = %s',
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT,
            SpyCategoryAttributeTableMap::COL_FK_LOCALE,
            $idLocale,
        );
        $productStoresSubquery->where($where);
        $productStoresSubquery->addAsColumn('category_names', sprintf('GROUP_CONCAT(DISTINCT %s)', SpyCategoryAttributeTableMap::COL_NAME));
        $params = [];

        return $productStoresSubquery->createSelectSql($params);
    }

    /**
     * @return string
     */
    protected function createProductAttributesSubquery(): string
    {
        $productQuery = $this->getFactory()->getProductConcretePropelQuery()
            ->limit(1)
            ->orderBy(SpyProductTableMap::COL_ID_PRODUCT)
            ->where(sprintf(
                '%s = %s',
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            ));
        $productQuery->addSelectColumn(SpyProductTableMap::COL_ATTRIBUTES);
        $params = [];

        return $productQuery->createSelectSql($params);
    }

    /**
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     */
    protected function applyProductAbstractSearch(
        SpyMerchantProductAbstractQuery $merchantProductAbstractQuery,
        MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
    ): SpyMerchantProductAbstractQuery {
        $searchTerm = $merchantProductTableCriteriaTransfer->getSearchTerm();

        if (!$searchTerm) {
            return $merchantProductAbstractQuery;
        }

        $criteria = new Criteria();
        $productNameSearchCriterion = $this->getProductAbstractNameSearchCriteria($criteria, $searchTerm);
        $productSkuSearchCriterion = $this->getProductAbstractSkuSearchCriteria($criteria, $searchTerm);
        $productNameSearchCriterion->addOr($productSkuSearchCriterion);

        $merchantProductAbstractQuery->addAnd($productNameSearchCriterion);

        return $merchantProductAbstractQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     * @param string $searchTerm
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion
     */
    protected function getProductAbstractNameSearchCriteria(Criteria $criteria, string $searchTerm): LikeCriterion
    {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion $likeCriterion */
        $likeCriterion = $criteria->getNewCriterion(
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
            '%' . $searchTerm . '%',
            Criteria::LIKE,
        );

        return $likeCriterion->setIgnoreCase(true);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     * @param string $searchTerm
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion
     */
    protected function getProductAbstractSkuSearchCriteria(Criteria $criteria, string $searchTerm): LikeCriterion
    {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion $likeCriterion */
        $likeCriterion = $criteria->getNewCriterion(
            SpyProductAbstractTableMap::COL_SKU,
            '%' . $searchTerm . '%',
            Criteria::LIKE,
        );

        return $likeCriterion->setIgnoreCase(true);
    }

    /**
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     */
    protected function addProductAbstractSorting(
        SpyMerchantProductAbstractQuery $merchantProductAbstractQuery,
        MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
    ): SpyMerchantProductAbstractQuery {
        $orderDirection = $merchantProductTableCriteriaTransfer->getOrderDirection() ?? Criteria::DESC;
        $orderColumn = $merchantProductTableCriteriaTransfer->getOrderBy();
        if ($orderColumn === null) {
            $merchantProductAbstractQuery->orderByIdMerchantProductAbstract($orderDirection);

            return $merchantProductAbstractQuery;
        }

        $orderColumn = ProductAbstractTableDataMapper::PRODUCT_ABSTRACT_DATA_COLUMN_MAP[$orderColumn] ?? $orderColumn;

        if ($orderColumn === SpyProductAbstractTableMap::COL_SKU) {
            /** @var \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery */
            $merchantProductAbstractQuery = $this->addNaturalSorting($merchantProductAbstractQuery, $orderColumn, $orderDirection);
        }

        $merchantProductAbstractQuery->orderBy($orderColumn, $orderDirection);

        return $merchantProductAbstractQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<mixed> $query
     * @param string $orderColumn
     * @param string $orderDirection
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria<mixed>
     */
    protected function addNaturalSorting(
        ModelCriteria $query,
        string $orderColumn,
        string $orderDirection
    ): ModelCriteria {
        if ($orderDirection === Criteria::ASC) {
            $query->addAscendingOrderByColumn("LENGTH($orderColumn)");
        }
        if ($orderDirection === Criteria::DESC) {
            $query->addDescendingOrderByColumn("LENGTH($orderColumn)");
        }

        // DISTINCT query requires it to be in SELECT
        $query->withColumn("LENGTH($orderColumn)");

        return $query;
    }

    /**
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     */
    protected function addProductAbstractFilters(
        SpyMerchantProductAbstractQuery $merchantProductAbstractQuery,
        MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
    ): SpyMerchantProductAbstractQuery {
        $merchantProductAbstractQuery = $this->addIsVisibleProductAbstractFilter(
            $merchantProductAbstractQuery,
            $merchantProductTableCriteriaTransfer,
        );
        $merchantProductAbstractQuery = $this->addInStoresProductAbstractFilter(
            $merchantProductAbstractQuery,
            $merchantProductTableCriteriaTransfer,
        );

        $merchantProductAbstractQuery = $this->addInCategoriesProductAbstractFilter(
            $merchantProductAbstractQuery,
            $merchantProductTableCriteriaTransfer,
        );

        $merchantProductAbstractQuery = $this->addInApprovalStatusesProductAbstractFilter(
            $merchantProductAbstractQuery,
            $merchantProductTableCriteriaTransfer,
        );

        return $merchantProductAbstractQuery;
    }

    /**
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     */
    protected function addIsVisibleProductAbstractFilter(
        SpyMerchantProductAbstractQuery $merchantProductAbstractQuery,
        MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
    ): SpyMerchantProductAbstractQuery {
        $isVisible = $merchantProductTableCriteriaTransfer->getFilterIsVisible();

        if ($isVisible === null) {
            return $merchantProductAbstractQuery;
        }

        /** @var literal-string $where */
        $where = sprintf(
            '(%s) %s 0',
            $this->createActiveProductsCountSubquery(),
            $isVisible ? '>' : '=',
        );
        $merchantProductAbstractQuery->where($where);

        return $merchantProductAbstractQuery;
    }

    /**
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     */
    protected function addInStoresProductAbstractFilter(
        SpyMerchantProductAbstractQuery $merchantProductAbstractQuery,
        MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
    ): SpyMerchantProductAbstractQuery {
        if (!$merchantProductTableCriteriaTransfer->getFilterInStores()) {
            return $merchantProductAbstractQuery;
        }

        $merchantProductAbstractQuery->useProductAbstractQuery()
                ->useSpyProductAbstractStoreQuery()
                    ->filterByFkStore_In($merchantProductTableCriteriaTransfer->getFilterInStores())
                    ->distinct()
                ->endUse()
            ->endUse();

        return $merchantProductAbstractQuery;
    }

    /**
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     */
    protected function addInCategoriesProductAbstractFilter(
        SpyMerchantProductAbstractQuery $merchantProductAbstractQuery,
        MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
    ): SpyMerchantProductAbstractQuery {
        if (!$merchantProductTableCriteriaTransfer->getFilterInCategories()) {
            return $merchantProductAbstractQuery;
        }

        $merchantProductAbstractQuery->useProductAbstractQuery()
                ->useSpyProductCategoryQuery()
                    ->filterByFkCategory_In($merchantProductTableCriteriaTransfer->getFilterInCategories())
                ->endUse()
            ->endUse();

        return $merchantProductAbstractQuery;
    }

    /**
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     */
    protected function addInApprovalStatusesProductAbstractFilter(
        SpyMerchantProductAbstractQuery $merchantProductAbstractQuery,
        MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
    ): SpyMerchantProductAbstractQuery {
        if (!$merchantProductTableCriteriaTransfer->getFilterInApprovalStatuses()) {
            return $merchantProductAbstractQuery;
        }

        $merchantProductAbstractQuery->useProductAbstractQuery()
            ->filterByApprovalStatus_In($merchantProductTableCriteriaTransfer->getFilterInApprovalStatuses())
            ->endUse();

        return $merchantProductAbstractQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductTableData(ProductTableCriteriaTransfer $productTableCriteriaTransfer): ProductConcreteCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\LocaleTransfer $localeTransfer */
        $localeTransfer = $productTableCriteriaTransfer->getLocaleOrFail();

        $productConcreteQuery = $this->buildProductTableBaseQuery($productTableCriteriaTransfer, $localeTransfer);
        $productConcreteQuery = $this->applyProductConcreteSearch($productConcreteQuery, $productTableCriteriaTransfer);
        $productConcreteQuery = $this->applyProductConcreteSorting($productConcreteQuery, $productTableCriteriaTransfer);
        $productConcreteQuery = $this->applyProductConcreteFilters($productConcreteQuery, $productTableCriteriaTransfer);

        $propelPager = $productConcreteQuery->paginate(
            $productTableCriteriaTransfer->getPageOrFail(),
            $productTableCriteriaTransfer->getPageSizeOrFail(),
        );
        $paginationTransfer = $this->getFactory()
            ->createPropelModelPagerMapper()
            ->mapPropelModelPagerToPaginationTransfer($propelPager, new PaginationTransfer());

        $productConcreteCollectionTransfer = $this->getFactory()
            ->createProductTableDataMapper()
            ->mapProductTableDataArrayToProductConcreteCollectionTransfer(
                $propelPager->getResults()->getData(),
                new ProductConcreteCollectionTransfer(),
                $localeTransfer,
            );
        $productConcreteCollectionTransfer->setPagination($paginationTransfer);

        return $productConcreteCollectionTransfer;
    }

    /**
     * @module Product
     * @module ProductImage
     * @module ProductValidity
     * @module MerchantProduct
     *
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     */
    protected function buildProductTableBaseQuery(
        ProductTableCriteriaTransfer $productTableCriteriaTransfer,
        LocaleTransfer $localeTransfer
    ): SpyProductQuery {
        $idLocale = $localeTransfer->getIdLocaleOrFail();
        $idMerchant = $productTableCriteriaTransfer->getIdMerchantOrFail();

        $productConcreteQuery = $this->getFactory()->getProductConcretePropelQuery();
        $productConcreteQuery->leftJoinSpyProductValidity()
            ->useSpyProductAbstractQuery()
                ->joinSpyMerchantProductAbstract()
                ->useSpyMerchantProductAbstractQuery()
                    ->filterByFkMerchant($idMerchant)
                ->endUse()
            ->endUse()
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->leftJoinSpyProductLocalizedAttributes(static::RELATION_LOCALE_FALLBACK)
            ->addJoinCondition(
                static::RELATION_LOCALE_FALLBACK,
                sprintf('(%s is null OR %s = \'\')', SpyProductLocalizedAttributesTableMap::COL_NAME, SpyProductLocalizedAttributesTableMap::COL_NAME),
            )
            ->addJoinCondition(static::RELATION_LOCALE_FALLBACK, static::RELATION_LOCALE_FALLBACK . '.name is not null')
            ->addJoinCondition(static::RELATION_LOCALE_FALLBACK, static::RELATION_LOCALE_FALLBACK . '.name != \'\'')
            ->addAsColumn(ProductConcreteTransfer::ID_PRODUCT_CONCRETE, SpyProductTableMap::COL_ID_PRODUCT)
            ->addAsColumn(ProductConcreteTransfer::FK_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->addAsColumn(ProductConcreteTransfer::SKU, SpyProductTableMap::COL_SKU)
            ->addAsColumn(ProductConcreteTransfer::IS_ACTIVE, SpyProductTableMap::COL_IS_ACTIVE)
            ->addAsColumn(ProductConcreteTransfer::ATTRIBUTES, SpyProductTableMap::COL_ATTRIBUTES)
            ->addAsColumn(ProductConcreteTransfer::LOCALIZED_ATTRIBUTES, SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES)
            ->addAsColumn(LocalizedAttributesTransfer::NAME, SpyProductLocalizedAttributesTableMap::COL_NAME)
            ->addAsColumn(ProductConcreteTransfer::VALID_FROM, SpyProductValidityTableMap::COL_VALID_FROM)
            ->addAsColumn(ProductConcreteTransfer::VALID_TO, SpyProductValidityTableMap::COL_VALID_TO)
            ->addAsColumn(ProductImageTransfer::EXTERNAL_URL_SMALL, sprintf('(%s)', $this->createProductImagesSubquery($idLocale)))
            ->select([
                ProductConcreteTransfer::ID_PRODUCT_CONCRETE,
                ProductConcreteTransfer::FK_PRODUCT_ABSTRACT,
                ProductConcreteTransfer::SKU,
                ProductConcreteTransfer::IS_ACTIVE,
                ProductConcreteTransfer::ATTRIBUTES,
                ProductConcreteTransfer::LOCALIZED_ATTRIBUTES,
                ProductConcreteTransfer::VALID_FROM,
                ProductConcreteTransfer::VALID_TO,
                ProductImageTransfer::EXTERNAL_URL_SMALL,
            ])
            ->withColumn(static::RELATION_LOCALE_FALLBACK . '.name', static::COL_NAME_FALLBACK);

        if ($productTableCriteriaTransfer->getIdProductAbstract() !== null) {
            $productConcreteQuery->filterByFkProductAbstract($productTableCriteriaTransfer->getIdProductAbstractOrFail());
        }

        return $productConcreteQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct> $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     */
    protected function applyProductConcreteSearch(
        SpyProductQuery $productConcreteQuery,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): SpyProductQuery {
        $searchTerm = $productTableCriteriaTransfer->getSearchTerm();

        if (!$searchTerm) {
            return $productConcreteQuery;
        }

        $criteria = new Criteria();
        $productNameSearchCriterion = $this->getProductConcreteNameSearchCriteria($criteria, $searchTerm);
        $productSkuSearchCriterion = $this->getProductConcreteSkuSearchCriteria($criteria, $searchTerm);
        $productNameSearchCriterion->addOr($productSkuSearchCriterion);

        return $productConcreteQuery->addAnd($productNameSearchCriterion);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     * @param string $searchTerm
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion
     */
    protected function getProductConcreteNameSearchCriteria(Criteria $criteria, string $searchTerm): LikeCriterion
    {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion $likeCriterion */
        $likeCriterion = $criteria->getNewCriterion(
            SpyProductLocalizedAttributesTableMap::COL_NAME,
            '%' . $searchTerm . '%',
            Criteria::LIKE,
        );

        return $likeCriterion->setIgnoreCase(true);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     * @param string $searchTerm
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion
     */
    protected function getProductConcreteSkuSearchCriteria(Criteria $criteria, string $searchTerm): LikeCriterion
    {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion $likeCriterion */
        $likeCriterion = $criteria->getNewCriterion(
            SpyProductTableMap::COL_SKU,
            '%' . $searchTerm . '%',
            Criteria::LIKE,
        );

        return $likeCriterion->setIgnoreCase(true);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct> $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     */
    protected function applyProductConcreteSorting(
        SpyProductQuery $productConcreteQuery,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): SpyProductQuery {
        $orderColumn = $productTableCriteriaTransfer->getOrderBy() ?? static::COL_KEY_PRODUCT_SKU;
        $orderDirection = $productTableCriteriaTransfer->getOrderDirection() ?? Criteria::DESC;

        if (!$orderColumn || !$orderDirection) {
            return $productConcreteQuery;
        }

        $orderColumn = ProductTableDataMapper::PRODUCT_DATA_COLUMN_MAP[$orderColumn] ?? $orderColumn;

        if ($orderColumn === SpyProductTableMap::COL_SKU) {
            /** @var \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct> $productConcreteQuery */
            $productConcreteQuery = $this->addNaturalSorting($productConcreteQuery, $orderColumn, $orderDirection);
        }

        $productConcreteQuery->orderBy($orderColumn, $orderDirection);

        return $productConcreteQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct> $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     */
    protected function applyProductConcreteFilters(
        SpyProductQuery $productConcreteQuery,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): SpyProductQuery {
        $productConcreteQuery = $this->addIsActiveProductFilter($productConcreteQuery, $productTableCriteriaTransfer);
        $productConcreteQuery = $this->addValidityProductFilter($productConcreteQuery, $productTableCriteriaTransfer);

        return $productConcreteQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct> $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     */
    protected function addIsActiveProductFilter(
        SpyProductQuery $productConcreteQuery,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): SpyProductQuery {
        $filterValue = $productTableCriteriaTransfer->getFilterIsActive();

        if ($filterValue !== null) {
            $productConcreteQuery->filterByIsActive($filterValue);
        }

        return $productConcreteQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct> $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     */
    protected function addValidityProductFilter(
        SpyProductQuery $productConcreteQuery,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): SpyProductQuery {
        $criteriaRangeFilterTransfer = $productTableCriteriaTransfer->getFilterValidity();

        if (!$criteriaRangeFilterTransfer) {
            return $productConcreteQuery;
        }

        if ($criteriaRangeFilterTransfer->getFrom()) {
            $productConcreteQuery->useSpyProductValidityQuery()
                    ->filterByValidFrom($criteriaRangeFilterTransfer->getFrom(), Criteria::GREATER_EQUAL)
                ->endUse();
        }

        if ($criteriaRangeFilterTransfer->getTo()) {
            $productConcreteQuery->useSpyProductValidityQuery()
                    ->filterByValidTo($criteriaRangeFilterTransfer->getTo(), Criteria::LESS_THAN)
                ->endUse();
        }

        return $productConcreteQuery;
    }
}
