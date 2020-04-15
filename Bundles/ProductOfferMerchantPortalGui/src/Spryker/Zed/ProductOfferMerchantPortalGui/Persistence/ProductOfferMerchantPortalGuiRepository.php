<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableDataTransfer;
use Generated\Shared\Transfer\ProductTableRowDataTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductValidity\Persistence\Map\SpyProductValidityTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Util\PropelModelPager;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\Propel\ProductTableDataMapper;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiPersistenceFactory getFactory()
 */
class ProductOfferMerchantPortalGuiRepository extends AbstractRepository implements ProductOfferMerchantPortalGuiRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    public function getProductTableData(ProductTableCriteriaTransfer $productTableCriteriaTransfer): ProductTableDataTransfer
    {
        $productConcreteMapper = $this->getFactory()->createProductTableDataMapper();

        $productConcreteQuery = $this->buildProductTableBaseQuery($productTableCriteriaTransfer);
        $productConcreteQuery = $this->applySearch($productConcreteQuery, $productTableCriteriaTransfer);
        $productConcreteQuery = $this->addFilters($productConcreteQuery, $productTableCriteriaTransfer);
        $productConcreteQuery = $this->addSorting($productConcreteQuery, $productTableCriteriaTransfer);

        $paginationTransfer = $productTableCriteriaTransfer->requirePagination()->getPagination();
        $propelPager = $this->getPagerForQuery($productConcreteQuery, $paginationTransfer);
        $paginationTransfer = $this->hydratePaginationTransfer($paginationTransfer, $propelPager);

        $productTableDataTransfer = $productConcreteMapper->mapProductTableDataArrayToTableDataTransfer(
            $propelPager->getResults()->getData(),
            new ProductTableDataTransfer()
        );
        $productTableDataTransfer->setPagination($paginationTransfer);

        return $productTableDataTransfer;
    }

    /**
     * @module ProductOffer
     * @module ProductImage
     *
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function buildProductTableBaseQuery(ProductTableCriteriaTransfer $productTableCriteriaTransfer): SpyProductQuery
    {
        $productConcreteQuery = $this->getFactory()->getProductConcretePropelQuery();
        $localeId = $productTableCriteriaTransfer->requireLocale()->getLocale()->requireIdLocale()->getIdLocale();
        $merchantId = $productTableCriteriaTransfer->requireMerchantUser()->getMerchantUser()->requireIdMerchant()->getIdMerchant();

        $productConcreteQuery = $this->addLocalizedAttributesToProductTableQuery($productConcreteQuery, $localeId);
        $productConcreteQuery->leftJoinSpyProductValidity()
            ->addAsColumn(ProductTableRowDataTransfer::SKU, SpyProductTableMap::COL_SKU)
            ->addAsColumn(ProductTableRowDataTransfer::PRODUCT_ABSTRACT_ATTRIBUTES, SpyProductAbstractTableMap::COL_ATTRIBUTES)
            ->addAsColumn(ProductTableRowDataTransfer::PRODUCT_CONCRETE_ATTRIBUTES, SpyProductTableMap::COL_ATTRIBUTES)
            ->addAsColumn(ProductTableRowDataTransfer::PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES, SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES)
            ->addAsColumn(ProductTableRowDataTransfer::PRODUCT_CONCRETE_LOCALIZED_ATTRIBUTES, SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES)
            ->addAsColumn(ProductTableRowDataTransfer::IS_ACTIVE, SpyProductTableMap::COL_IS_ACTIVE)
            ->addAsColumn(ProductTableRowDataTransfer::NAME, SpyProductLocalizedAttributesTableMap::COL_NAME)
            ->addAsColumn(ProductTableRowDataTransfer::STORES, sprintf('(%s)', $this->createProductStoresSubquery()))
            ->addAsColumn(ProductTableRowDataTransfer::IMAGE, sprintf('(%s)', $this->createProductImagesSubquery($localeId)))
            ->addAsColumn(ProductTableRowDataTransfer::OFFERS_COUNT, sprintf('(%s)', $this->createProductOffersCountSubquery($merchantId)))
            ->addAsColumn(ProductTableRowDataTransfer::VALID_FROM, SpyProductValidityTableMap::COL_VALID_FROM)
            ->addAsColumn(ProductTableRowDataTransfer::VALID_TO, SpyProductValidityTableMap::COL_VALID_TO)
            ->where(sprintf('(%s) IS NOT NULL', $this->createProductStoresSubquery()))
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);

        return $productConcreteQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param int $localeId
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addLocalizedAttributesToProductTableQuery(SpyProductQuery $productConcreteQuery, int $localeId): SpyProductQuery
    {
        $productConcreteQuery->joinSpyProductLocalizedAttributes()
            ->addJoinCondition(
                'SpyProductLocalizedAttributes',
                sprintf('%s = ?', SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE),
                $localeId
            )
            ->joinSpyProductAbstract()
            ->useSpyProductAbstractQuery()
            ->joinSpyProductAbstractLocalizedAttributes()
            ->addJoinCondition(
                'SpyProductAbstractLocalizedAttributes',
                sprintf('%s = ?', SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE),
                $localeId
            )
            ->endUse();

        return $productConcreteQuery;
    }

    /**
     * @return string
     */
    protected function createProductStoresSubquery(): string
    {
        $productStoresSubquery = $this->getFactory()->getStorePropelQuery()
            ->joinSpyProductAbstractStore()
            ->useSpyProductAbstractStoreQuery()
            ->joinSpyProductAbstract()
            ->endUse()
            ->addAsColumn('stores', sprintf('GROUP_CONCAT(DISTINCT %s)', SpyStoreTableMap::COL_NAME))
            ->where(sprintf('%s = %s', SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT));
        $params = [];

        return $productStoresSubquery->createSelectSql($params);
    }

    /**
     * @param int $localeId
     *
     * @return string
     */
    protected function createProductImagesSubquery(int $localeId): string
    {
        $productImagesSubquery = $this->getFactory()->getProductImagePropelQuery()
            ->joinSpyProductImageSetToProductImage()
            ->useSpyProductImageSetToProductImageQuery()
            ->joinSpyProductImageSet()
            ->endUse()
            ->where(sprintf(
                '%1$s = %2$s AND (%3$s = %4$d OR %3$s IS NULL)',
                SpyProductImageSetTableMap::COL_FK_PRODUCT,
                SpyProductTableMap::COL_ID_PRODUCT,
                SpyProductImageSetTableMap::COL_FK_LOCALE,
                $localeId
            ))
            ->addSelectColumn(SpyProductImageTableMap::COL_EXTERNAL_URL_SMALL)
            ->orderBy(SpyProductImageSetTableMap::COL_FK_LOCALE)
            ->limit(1);
        $params = [];

        return $productImagesSubquery->createSelectSql($params);
    }

    /**
     * @param int $merchantId
     *
     * @return string
     */
    protected function createProductOffersCountSubquery(int $merchantId): string
    {
        $productOffersSubquery = $this->getFactory()->getProductOfferPropelQuery()
            ->addAsColumn('offers_count', 'COUNT(*)')
            ->where(sprintf(
                '%s = %s AND %s = %s',
                SpyProductOfferTableMap::COL_CONCRETE_SKU,
                SpyProductTableMap::COL_SKU,
                SpyProductOfferTableMap::COL_FK_MERCHANT,
                $merchantId
            ));
        $params = [];

        return $productOffersSubquery->createSelectSql($params);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addSorting(
        SpyProductQuery $productConcreteQuery,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): SpyProductQuery {
        if (!$productTableCriteriaTransfer->getOrderBy()) {
            return $productConcreteQuery;
        }

        foreach ($productTableCriteriaTransfer->getOrderBy() as $field => $direction) {
            $sortField = ProductTableDataMapper::PRODUCT_DATA_COLUMN_MAP[$field] ?? $field;

            if (!$sortField) {
                continue;
            }

            $productConcreteQuery->orderBy($sortField, $direction);
        }

        return $productConcreteQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\Util\PropelModelPager
     */
    protected function getPagerForQuery(ModelCriteria $query, PaginationTransfer $paginationTransfer): PropelModelPager
    {
        return $query->paginate(
            $paginationTransfer->requirePage()->getPage(),
            $paginationTransfer->requireMaxPerPage()->getMaxPerPage()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param \Propel\Runtime\Util\PropelModelPager $propelPager
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function hydratePaginationTransfer(
        PaginationTransfer $paginationTransfer,
        PropelModelPager $propelPager
    ): PaginationTransfer {
        $paginationTransfer->setNbResults($propelPager->getNbResults())
            ->setFirstIndex($propelPager->getFirstIndex())
            ->setLastIndex($propelPager->getLastIndex())
            ->setFirstPage($propelPager->getFirstPage())
            ->setLastPage($propelPager->getLastPage())
            ->setNextPage($propelPager->getNextPage())
            ->setPreviousPage($propelPager->getPreviousPage());

        return $paginationTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addFilters(SpyProductQuery $productConcreteQuery, ProductTableCriteriaTransfer $productTableCriteriaTransfer): SpyProductQuery
    {
        $productConcreteQuery = $this->addIsActiveFilter($productConcreteQuery, $productTableCriteriaTransfer);
        $productConcreteQuery = $this->addHasOffersFilter($productConcreteQuery, $productTableCriteriaTransfer);

        return $productConcreteQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function applySearch(SpyProductQuery $productConcreteQuery, ProductTableCriteriaTransfer $productTableCriteriaTransfer): SpyProductQuery
    {
        $searchTerm = $productTableCriteriaTransfer->getSearchTerm();

        if (!$searchTerm) {
            return $productConcreteQuery;
        }

        $criteria = new Criteria();
        $productNameSearchCriterion = $criteria->getNewCriterion(
            SpyProductLocalizedAttributesTableMap::COL_NAME,
            '%' . $searchTerm . '%',
            Criteria::LIKE
        );
        $productSkuSearchCriterion = $criteria->getNewCriterion(
            SpyProductTableMap::COL_SKU,
            '%' . $searchTerm . '%',
            Criteria::LIKE
        );
        $productNameSearchCriterion->addOr($productSkuSearchCriterion);

        return $productConcreteQuery->addAnd($productNameSearchCriterion);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addIsActiveFilter(SpyProductQuery $productConcreteQuery, ProductTableCriteriaTransfer $productTableCriteriaTransfer): SpyProductQuery
    {
        if ($productTableCriteriaTransfer->getIsActive() === null) {
            return $productConcreteQuery;
        }

        $productConcreteQuery->filterByIsActive(
            $productTableCriteriaTransfer->getIsActive()
        );

        return $productConcreteQuery;
    }

    /**
     * @module ProductOffer
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addHasOffersFilter(SpyProductQuery $productConcreteQuery, ProductTableCriteriaTransfer $productTableCriteriaTransfer): SpyProductQuery
    {
        $productConcreteHasOffers = $productTableCriteriaTransfer->getHasOffers();

        if ($productConcreteHasOffers === null) {
            return $productConcreteQuery;
        }

        $merchantUserId = $productTableCriteriaTransfer->requireMerchantUser()->getMerchantUser()->requireIdMerchant()->getIdMerchant();
        $productConcreteQuery->where(
            sprintf(
                '(%s) %s 0',
                $this->createProductOffersCountSubquery($merchantUserId),
                $productConcreteHasOffers ? '>' : '='
            )
        );

        return $productConcreteQuery;
    }
}
