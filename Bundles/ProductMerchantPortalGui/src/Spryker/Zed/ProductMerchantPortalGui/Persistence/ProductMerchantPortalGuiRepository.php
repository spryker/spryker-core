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
use Generated\Shared\Transfer\PriceProductTableCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductDefaultTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery;
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
    protected const SUFFIX_PRICE_TYPE_NET = '_net';
    protected const SUFFIX_PRICE_TYPE_GROSS = '_gross';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider::COL_KEY_SKU
     */
    protected const COL_KEY_PRODUCT_ABSTRACT_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_SKU
     */
    protected const COL_KEY_PRODUCT_SKU = 'sku';

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
            $merchantProductTableCriteriaTransfer
        );
        $merchantProductAbstractPropelQuery = $this->addProductAbstractSorting(
            $merchantProductAbstractPropelQuery,
            $merchantProductTableCriteriaTransfer
        );
        $merchantProductAbstractPropelQuery = $this->addProductAbstractFilters(
            $merchantProductAbstractPropelQuery,
            $merchantProductTableCriteriaTransfer
        );

        $propelPager = $merchantProductAbstractPropelQuery->paginate(
            $merchantProductTableCriteriaTransfer->getPageOrFail(),
            $merchantProductTableCriteriaTransfer->getPageSizeOrFail()
        );

        $paginationTransfer = $this->getFactory()->createPropelModelPagerMapper()->mapPropelModelPagerToPaginationTransfer(
            $propelPager,
            new PaginationTransfer()
        );
        $productAbstractCollectionTransfer = $this->getFactory()
            ->createProductAbstractTableDataMapper()
            ->mapProductAbstractTableDataArrayToProductAbstractCollectionTransfer(
                $propelPager->getResults()->getData(),
                new ProductAbstractCollectionTransfer()
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
     * @phpstan-return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     *
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
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
            ]);

        $merchantProductAbstractPropelQuery->addAsColumn(ProductAbstractTransfer::ID_PRODUCT_ABSTRACT, SpyMerchantProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->addAsColumn(ProductAbstractTransfer::SKU, SpyProductAbstractTableMap::COL_SKU)
            ->addAsColumn(ProductImageTransfer::EXTERNAL_URL_SMALL, sprintf('(%s)', $this->createProductImagesSubquery($idLocale)))
            ->addAsColumn(ProductAbstractTransfer::NAME, SpyProductAbstractLocalizedAttributesTableMap::COL_NAME)
            ->addAsColumn(ProductAbstractTransfer::ATTRIBUTES, sprintf('(%s)', $this->createProductAttributesSubquery()))
            ->addAsColumn(ProductAbstractTransfer::CONCRETE_PRODUCT_COUNT, sprintf('(%s)', $this->createProductsCountSubquery()))
            ->addAsColumn(ProductAbstractTransfer::CATEGORY_NAMES, sprintf('(%s)', $this->createProductAbstractCategoriesSubquery($idLocale)))
            ->addAsColumn(ProductAbstractTransfer::STORE_NAMES, sprintf('(%s)', $this->createProductAbstractStoresSubquery()))
            ->addAsColumn(ProductAbstractTransfer::IS_ACTIVE, sprintf('(%s) > 0', $this->createActiveProductsCountSubquery()));

        return $merchantProductAbstractPropelQuery;
    }

    /**
     * @param int $idLocale
     *
     * @return string
     */
    protected function createProductImagesSubquery(int $idLocale): string
    {
        $productImagesSubquery = $this->getFactory()->getProductImagePropelQuery()
            ->joinSpyProductImageSetToProductImage()
            ->useSpyProductImageSetToProductImageQuery()
            ->joinSpyProductImageSet()
            ->endUse()
            ->where(sprintf(
                '%1$s = %2$s AND (%3$s = %4$d OR %3$s IS NULL)',
                SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductImageSetTableMap::COL_FK_LOCALE,
                $idLocale
            ))
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

        $productQuery->where(
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT . ' = ' . SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT
        );

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

        $productQuery->where(
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT . ' = ' . SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT
            . ' AND ' . SpyProductTableMap::COL_IS_ACTIVE
        );

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

        $productStoresSubquery->where(sprintf(
            '%s = %s AND %s = %s',
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT,
            SpyCategoryAttributeTableMap::COL_FK_LOCALE,
            $idLocale
        ));
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
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT
            ));
        $productQuery->addSelectColumn(SpyProductTableMap::COL_ATTRIBUTES);
        $params = [];

        return $productQuery->createSelectSql($params);
    }

    /**
     * @phpstan-param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery
     *
     * @phpstan-return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     *
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
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
            Criteria::LIKE
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
            Criteria::LIKE
        );

        return $likeCriterion->setIgnoreCase(true);
    }

    /**
     * @phpstan-param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery
     *
     * @phpstan-return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     *
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    protected function addProductAbstractSorting(
        SpyMerchantProductAbstractQuery $merchantProductAbstractQuery,
        MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
    ): SpyMerchantProductAbstractQuery {
        $orderColumn = $merchantProductTableCriteriaTransfer->getOrderBy() ?? static::COL_KEY_PRODUCT_ABSTRACT_SKU;
        $orderDirection = $merchantProductTableCriteriaTransfer->getOrderDirection() ?? Criteria::DESC;

        if (!$orderColumn || !$orderDirection) {
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
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<mixed> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<mixed>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param string $orderColumn
     * @param string $orderDirection
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
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
     * @phpstan-param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery
     *
     * @phpstan-return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     *
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    protected function addProductAbstractFilters(
        SpyMerchantProductAbstractQuery $merchantProductAbstractQuery,
        MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
    ): SpyMerchantProductAbstractQuery {
        $merchantProductAbstractQuery = $this->addIsVisibleProductAbstractFilter(
            $merchantProductAbstractQuery,
            $merchantProductTableCriteriaTransfer
        );
        $merchantProductAbstractQuery = $this->addInStoresProductAbstractFilter(
            $merchantProductAbstractQuery,
            $merchantProductTableCriteriaTransfer
        );

        $merchantProductAbstractQuery = $this->addInCategoriesProductAbstractFilter(
            $merchantProductAbstractQuery,
            $merchantProductTableCriteriaTransfer
        );

        return $merchantProductAbstractQuery;
    }

    /**
     * @phpstan-param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery
     *
     * @phpstan-return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     *
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    protected function addIsVisibleProductAbstractFilter(
        SpyMerchantProductAbstractQuery $merchantProductAbstractQuery,
        MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
    ): SpyMerchantProductAbstractQuery {
        $isVisible = $merchantProductTableCriteriaTransfer->getFilterIsVisible();

        if ($isVisible === null) {
            return $merchantProductAbstractQuery;
        }

        $merchantProductAbstractQuery->where(
            sprintf(
                '(%s) %s 0',
                $this->createActiveProductsCountSubquery(),
                $isVisible ? '>' : '='
            )
        );

        return $merchantProductAbstractQuery;
    }

    /**
     * @phpstan-param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery
     *
     * @phpstan-return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     *
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
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
     * @phpstan-param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $merchantProductAbstractQuery
     *
     * @phpstan-return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     *
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer $merchantProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
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
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer
     */
    public function getPriceProductTableData(
        PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
    ): PriceProductTableViewCollectionTransfer {
        $priceProductDefaultQuery = $this->buildPriceProductTableBaseQuery($priceProductTableCriteriaTransfer);
        $priceProductDefaultQuery = $this->addPriceProductTableFilters(
            $priceProductDefaultQuery,
            $priceProductTableCriteriaTransfer
        );

        $propelPager = $priceProductDefaultQuery->paginate(
            $priceProductTableCriteriaTransfer->getPageOrFail(),
            $priceProductTableCriteriaTransfer->getPageSizeOrFail()
        );
        $paginationTransfer = $this->getFactory()->createPropelModelPagerMapper()->mapPropelModelPagerToPaginationTransfer(
            $propelPager,
            new PaginationTransfer()
        );

        $priceProductTableViewCollectionTransfer = $this->getFactory()
            ->createPriceProductTableDataMapper()
            ->mapPriceProductTableDataArrayToPriceProductTableViewCollectionTransfer(
                $propelPager->getResults()->getData(),
                new PriceProductTableViewCollectionTransfer()
            );
        $priceProductTableViewCollectionTransfer->setPagination($paginationTransfer);

        return $priceProductTableViewCollectionTransfer;
    }

    /**
     * @module PriceProduct
     * @module Store
     * @module Currency
     * @module MerchantProduct
     *
     * @phpstan-return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery<\Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery>
     *
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery
     */
    protected function buildPriceProductTableBaseQuery(
        PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
    ): SpyPriceProductDefaultQuery {
        $priceProductTableCriteriaTransfer->requireIdMerchant();
        $priceProductDefaultQuery = $this->getFactory()->getPriceProductDefaultPropelQuery();

        $priceProductDefaultQuery
            ->usePriceProductStoreQuery()
                ->usePriceProductQuery()
                    ->innerJoinPriceType()
                ->endUse()
                ->joinCurrency()
                ->joinStore()
                ->groupBy(SpyStoreTableMap::COL_NAME)
                ->groupBy(SpyCurrencyTableMap::COL_CODE)
            ->endUse()
            ->addAsColumn(PriceProductTableViewTransfer::STORE, SpyStoreTableMap::COL_NAME)
            ->addAsColumn(PriceProductTableViewTransfer::CURRENCY, SpyCurrencyTableMap::COL_CODE)
            ->select([SpyStoreTableMap::COL_NAME, SpyCurrencyTableMap::COL_CODE]);

        $priceProductDefaultQuery = $this->applyProductIdToPriceProductDefaultQuery($priceProductDefaultQuery, $priceProductTableCriteriaTransfer);

        $priceTypeTransfers = $this->getFactory()->getPriceProductFacade()->getPriceTypeValues();

        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            if (!$priceTypeTransfer->getIdPriceType()) {
                continue;
            }

            $priceProductDefaultQuery = $this->addPriceTypeColumns($priceProductDefaultQuery, $priceTypeTransfer);
        }

        $priceProductDefaultQuery->addAsColumn(
            PriceProductTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS,
            sprintf('GROUP_CONCAT(%s)', SpyPriceProductDefaultTableMap::COL_ID_PRICE_PRODUCT_DEFAULT)
        )->addAsColumn(
            PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS,
            sprintf(
                'GROUP_CONCAT(CONCAT(%s,\':\',%s))',
                SpyPriceTypeTableMap::COL_NAME,
                SpyPriceProductStoreTableMap::COL_ID_PRICE_PRODUCT_STORE
            )
        );

        if ($priceProductTableCriteriaTransfer->getOrderBy()) {
            $orderDirection = $priceProductTableCriteriaTransfer->getOrderDirection() ?? Criteria::ASC;
            $priceProductDefaultQuery->orderBy(
                $priceProductTableCriteriaTransfer->getOrderByOrFail(),
                $orderDirection
            );
        }

        return $priceProductDefaultQuery;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery $priceProductDefaultQuery
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery
     */
    protected function applyProductIdToPriceProductDefaultQuery(
        SpyPriceProductDefaultQuery $priceProductDefaultQuery,
        PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
    ): SpyPriceProductDefaultQuery {
        if ($priceProductTableCriteriaTransfer->getIdProductConcrete()) {
            $priceProductDefaultQuery->usePriceProductStoreQuery()
                ->usePriceProductQuery()
                    ->filterByFkProduct($priceProductTableCriteriaTransfer->getIdProductConcrete())
                    ->useProductQuery()
                        ->useSpyProductAbstractQuery()
                            ->useSpyMerchantProductAbstractQuery()
                                ->filterByFkMerchant($priceProductTableCriteriaTransfer->getIdMerchantOrFail())
                            ->endUse()
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->addAsColumn(PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE, SpyProductTableMap::COL_ID_PRODUCT);
        }

        if ($priceProductTableCriteriaTransfer->getIdProductAbstract()) {
            $priceProductDefaultQuery->usePriceProductStoreQuery()
                ->usePriceProductQuery()
                    ->filterByFkProductAbstract($priceProductTableCriteriaTransfer->getIdProductAbstract())
                    ->useSpyProductAbstractQuery()
                        ->useSpyMerchantProductAbstractQuery()
                            ->filterByFkMerchant($priceProductTableCriteriaTransfer->getIdMerchantOrFail())
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->addAsColumn(PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT, SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
        }

        return $priceProductDefaultQuery;
    }

    /**
     * @phpstan-param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery<\Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery> $priceProductDefaultQuery
     *
     * @phpstan-return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery<\Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery>
     *
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery $priceProductDefaultQuery
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery
     */
    protected function addPriceTypeColumns(
        SpyPriceProductDefaultQuery $priceProductDefaultQuery,
        PriceTypeTransfer $priceTypeTransfer
    ): SpyPriceProductDefaultQuery {
        $priceTypeName = mb_strtolower($priceTypeTransfer->getNameOrFail());
        $grossColumnName = $priceTypeName . static::SUFFIX_PRICE_TYPE_GROSS;
        $grossClause = sprintf(
            'MAX(CASE WHEN %s = %s THEN %s END)',
            SpyPriceProductTableMap::COL_FK_PRICE_TYPE,
            $priceTypeTransfer->getIdPriceType(),
            SpyPriceProductStoreTableMap::COL_GROSS_PRICE
        );

        $netColumnName = $priceTypeName . static::SUFFIX_PRICE_TYPE_NET;
        $netClause = sprintf(
            'MAX(CASE WHEN %s = %s THEN %s END)',
            SpyPriceProductTableMap::COL_FK_PRICE_TYPE,
            $priceTypeTransfer->getIdPriceType(),
            SpyPriceProductStoreTableMap::COL_NET_PRICE
        );

        $priceProductDefaultQuery->addAsColumn($grossColumnName, $grossClause)
            ->addAsColumn($netColumnName, $netClause);

        return $priceProductDefaultQuery;
    }

    /**
     * @phpstan-param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery<\Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery> $priceProductDefaultQuery
     *
     * @phpstan-return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery<\Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery>
     *
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery $priceProductDefaultQuery
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery
     */
    protected function addPriceProductTableFilters(
        SpyPriceProductDefaultQuery $priceProductDefaultQuery,
        PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
    ): SpyPriceProductDefaultQuery {
        if ($priceProductTableCriteriaTransfer->getFilterInStores()) {
            $priceProductDefaultQuery->joinPriceProductStore()
                ->usePriceProductStoreQuery()
                    ->filterByFkStore_In($priceProductTableCriteriaTransfer->getFilterInStores())
                ->endUse();
        }

        if ($priceProductTableCriteriaTransfer->getFilterInCurrencies()) {
            $priceProductDefaultQuery->joinPriceProductStore()
                ->usePriceProductStoreQuery()
                    ->joinCurrency()
                    ->useCurrencyQuery()
                        ->filterByIdCurrency_In($priceProductTableCriteriaTransfer->getFilterInCurrencies())
                    ->endUse()
                ->endUse();
        }

        return $priceProductDefaultQuery;
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
            $productTableCriteriaTransfer->getPageSizeOrFail()
        );
        $paginationTransfer = $this->getFactory()
            ->createPropelModelPagerMapper()
            ->mapPropelModelPagerToPaginationTransfer($propelPager, new PaginationTransfer());

        $productConcreteCollectionTransfer = $this->getFactory()
            ->createProductTableDataMapper()
            ->mapProductTableDataArrayToProductConcreteCollectionTransfer(
                $propelPager->getResults()->getData(),
                new ProductConcreteCollectionTransfer(),
                $localeTransfer
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
     * @phpstan-return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     *
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function buildProductTableBaseQuery(
        ProductTableCriteriaTransfer $productTableCriteriaTransfer,
        LocaleTransfer $localeTransfer
    ): SpyProductQuery {
        /** @var int $idLocale */
        $idLocale = $localeTransfer->getIdLocaleOrFail();
        /** @var int $idMerchant */
        $idMerchant = $productTableCriteriaTransfer->getIdMerchantOrFail();
        /** @var int $idProductAbstract */
        $idProductAbstract = $productTableCriteriaTransfer->getIdProductAbstractOrFail();

        $productConcreteQuery = $this->getFactory()->getProductConcretePropelQuery();
        $productConcreteQuery->leftJoinSpyProductValidity()
            ->filterByFkProductAbstract($idProductAbstract)
            ->useSpyProductAbstractQuery()
                ->joinSpyMerchantProductAbstract()
                ->useSpyMerchantProductAbstractQuery()
                    ->filterByFkMerchant($idMerchant)
                ->endUse()
            ->endUse()
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
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
            ]);

        return $productConcreteQuery;
    }

    /**
     * @phpstan-param \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct> $productConcreteQuery
     *
     * @phpstan-return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
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
            Criteria::LIKE
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
            Criteria::LIKE
        );

        return $likeCriterion->setIgnoreCase(true);
    }

    /**
     * @phpstan-param \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct> $productConcreteQuery
     *
     * @phpstan-return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
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
     * @phpstan-param \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct> $productConcreteQuery
     *
     * @phpstan-return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
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
     * @phpstan-param \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct> $productConcreteQuery
     *
     * @phpstan-return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addIsActiveProductFilter(
        SpyProductQuery $productConcreteQuery,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): SpyProductQuery {
        $filterValue = $productTableCriteriaTransfer->getFilterIsActive();

        if (isset($filterValue)) {
            $productConcreteQuery->filterByIsActive($filterValue);
        }

        return $productConcreteQuery;
    }

    /**
     * @phpstan-param \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct> $productConcreteQuery
     *
     * @phpstan-return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
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
