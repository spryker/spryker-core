<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\MerchantProductTableCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewCollectionTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractStoreTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\LikeCriterion;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel\ProductAbstractTableDataMapper;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiPersistenceFactory getFactory()
 */
class ProductMerchantPortalGuiRepository extends AbstractRepository implements ProductMerchantPortalGuiRepositoryInterface
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider::COL_KEY_SKU
     */
    protected const COL_KEY_PRODUCT_ABSTRACT_SKU = 'sku';

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
            $merchantProductTableCriteriaTransfer->requirePage()->getPage(),
            $merchantProductTableCriteriaTransfer->requirePageSize()->getPageSize()
        );

        $paginationTransfer = $this->getFactory()->createPropelModelPagerMapper()->mapPropelModelPagerToPaginationTransfer($propelPager);
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
        $idLocale = $merchantProductTableCriteriaTransfer->requireLocale()->getLocale()->requireIdLocale()->getIdLocale();
        $idMerchant = $merchantProductTableCriteriaTransfer->requireIdMerchant()->getIdMerchant();

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
        $productStoresSubquery = $this->getFactory()->getProductCategoryPropelQuery()
            ->joinSpyCategory()
            ->useSpyCategoryQuery()
                ->joinAttribute()
            ->endUse()
            ->where(sprintf(
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
            $merchantProductAbstractQuery = $this->addNaturalSorting($merchantProductAbstractQuery, $orderColumn, $orderDirection);
        }

        $merchantProductAbstractQuery->orderBy($orderColumn, $orderDirection);

        return $merchantProductAbstractQuery;
    }

    /**
     * @phpstan-param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract> $query
     *
     * @phpstan-return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     *
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery $query
     * @param string $orderColumn
     * @param string $orderDirection
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    protected function addNaturalSorting(
        SpyMerchantProductAbstractQuery $query,
        string $orderColumn,
        string $orderDirection
    ): SpyMerchantProductAbstractQuery {
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
     * @param \Generated\Shared\Transfer\PriceProductAbstractTableCriteriaTransfer $priceProductAbstractTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductAbstractTableViewCollectionTransfer
     */
    public function getPriceProductAbstractTableData(
        PriceProductAbstractTableCriteriaTransfer $priceProductAbstractTableCriteriaTransfer
    ): PriceProductAbstractTableViewCollectionTransfer {
        $priceProductDefaultQueryPropelQuery = $this->buildPriceProductAbstractTableBaseQuery($priceProductAbstractTableCriteriaTransfer);
        $propelPager = $priceProductDefaultQueryPropelQuery->paginate(
            $priceProductAbstractTableCriteriaTransfer->requirePage()->getPage(),
            $priceProductAbstractTableCriteriaTransfer->requirePageSize()->getPageSize()
        );
        $paginationTransfer = $this->getFactory()->createPropelModelPagerMapper()->mapPropelModelPagerToPaginationTransfer($propelPager);

        $priceProductAbstractTableViewCollectionTransfer = $this->getFactory()
            ->createPriceProductAbstractTableDataMapper()
            ->mapPriceProductAbstractTableDataArrayToPriceProductAbstractTableViewCollectionTransfer(
                $propelPager->getResults()->getData(),
                new PriceProductAbstractTableViewCollectionTransfer()
            );
        $priceProductAbstractTableViewCollectionTransfer->setPagination($paginationTransfer);

        return $priceProductAbstractTableViewCollectionTransfer;
    }

    /**
     * @module PriceProduct
     * @module Store
     * @module Currency
     *
     * @phpstan-return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery<\Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery>
     *
     * @param \Generated\Shared\Transfer\PriceProductAbstractTableCriteriaTransfer $priceProductAbstractTableCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery
     */
    protected function buildPriceProductAbstractTableBaseQuery(
        PriceProductAbstractTableCriteriaTransfer $priceProductAbstractTableCriteriaTransfer
    ): SpyPriceProductDefaultQuery {
        $idMerchant = $priceProductAbstractTableCriteriaTransfer->requireIdMerchant()->getIdMerchant();
        $idProductAbstract = $priceProductAbstractTableCriteriaTransfer->requireIdProductAbstract()->getIdProductAbstract();
        $priceProductDefaultQueryPropelQuery = $this->getFactory()->getPriceProductDefaultPropelQuery();

        $priceProductDefaultQueryPropelQuery->joinPriceProductStore()
            ->usePriceProductStoreQuery()
                ->joinPriceProduct()
                ->usePriceProductQuery()
                    ->joinSpyProductAbstract()
                    ->useSpyProductAbstractQuery()
                        ->joinSpyMerchantProductAbstract()
                        ->useSpyMerchantProductAbstractQuery()
                            ->filterByFkMerchant($idMerchant)
                        ->endUse()
                    ->endUse()
                    ->filterByFkProductAbstract($idProductAbstract)
                ->endUse()
                ->joinCurrency()
                ->joinStore()
            ->endUse()
            ->addAsColumn(PriceProductAbstractTableViewTransfer::STORE, SpyStoreTableMap::COL_NAME)
            ->addAsColumn(PriceProductAbstractTableViewTransfer::CURRENCY, SpyCurrencyTableMap::COL_CODE)
            ->select([
                PriceProductAbstractTableViewTransfer::STORE,
                PriceProductAbstractTableViewTransfer::CURRENCY,
            ]);

        return $priceProductDefaultQueryPropelQuery;
    }
}
