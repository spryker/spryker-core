<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTableDataTransfer;
use Generated\Shared\Transfer\ProductOfferTableRowDataTransfer;
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
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOfferStock\Persistence\Map\SpyProductOfferStockTableMap;
use Orm\Zed\ProductOfferValidity\Persistence\Map\SpyProductOfferValidityTableMap;
use Orm\Zed\ProductValidity\Persistence\Map\SpyProductValidityTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Util\PropelModelPager;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\Propel\ProductOfferTableDataMapper;
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
        $productConcreteQuery = $this->applyProductSearch($productConcreteQuery, $productTableCriteriaTransfer);
        $productConcreteQuery = $this->addProductFilters($productConcreteQuery, $productTableCriteriaTransfer);
        $productConcreteQuery = $this->addProductSorting($productConcreteQuery, $productTableCriteriaTransfer);

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
    protected function addProductSorting(
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
    protected function addProductFilters(SpyProductQuery $productConcreteQuery, ProductTableCriteriaTransfer $productTableCriteriaTransfer): SpyProductQuery
    {
        $productConcreteQuery = $this->addIsActiveProductFilter($productConcreteQuery, $productTableCriteriaTransfer);
        $productConcreteQuery = $this->addHasOffersProductFilter($productConcreteQuery, $productTableCriteriaTransfer);

        return $productConcreteQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function applyProductSearch(
        SpyProductQuery $productConcreteQuery,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): SpyProductQuery {
        $searchTerm = $productTableCriteriaTransfer->getSearchTerm();

        if (!$searchTerm) {
            return $productConcreteQuery;
        }

        $criteria = new Criteria();
        $productNameSearchCriterion = $this->getProductNameSearchCriteria($criteria, $searchTerm);
        $productSkuSearchCriterion = $this->getProductSkuSearchCriteria($criteria, $searchTerm);
        $productNameSearchCriterion->addOr($productSkuSearchCriterion);

        return $productConcreteQuery->addAnd($productNameSearchCriterion);
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyProductOfferSearch(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        $searchTerm = $productOfferTableCriteriaTransfer->getSearchTerm();

        if (!$searchTerm) {
            return $productOfferQuery;
        }

        $criteria = new Criteria();
        $productNameSearchCriterion = $this->getProductNameSearchCriteria($criteria, $searchTerm);
        $productSkuSearchCriterion = $this->getProductSkuSearchCriteria($criteria, $searchTerm);
        $productOfferReferenceSearchCriterion = $this->getProductOfferReferenceSearchCriteria($criteria, $searchTerm);
        $productOfferMerchantSkuSearchCriterion = $this->getProductOfferMerchantSkuSearchCriteria($criteria, $searchTerm);

        $productNameSearchCriterion->addOr($productSkuSearchCriterion);
        $productSkuSearchCriterion->addOr($productOfferReferenceSearchCriterion);
        $productOfferReferenceSearchCriterion->addOr($productOfferMerchantSkuSearchCriterion);

        return $productOfferQuery->add($productNameSearchCriterion);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addIsActiveProductFilter(
        SpyProductQuery $productConcreteQuery,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): SpyProductQuery {
        if (!$productTableCriteriaTransfer->isPropertyModified(ProductTableCriteriaTransfer::IS_ACTIVE)) {
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
    protected function addHasOffersProductFilter(
        SpyProductQuery $productConcreteQuery,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): SpyProductQuery {
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

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTableDataTransfer
     */
    public function getProductOfferTableData(ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer): ProductOfferTableDataTransfer
    {
        $productOfferQuery = $this->buildProductOfferTableBaseQuery($productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->applyProductOfferSearch($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addProductOfferFilters($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addProductOfferSorting($productOfferQuery, $productOfferTableCriteriaTransfer);

        $paginationTransfer = $productOfferTableCriteriaTransfer->requirePagination()->getPagination();
        $propelPager = $this->getPagerForQuery($productOfferQuery, $paginationTransfer);
        $paginationTransfer = $this->hydratePaginationTransfer($paginationTransfer, $propelPager);

        $productOfferTableDataTransfer = $this->getFactory()
            ->createProductOfferTableDataMapper()
            ->mapProductOfferTableDataArrayToTableDataTransfer(
                $propelPager->getResults()->getData(),
                new ProductOfferTableDataTransfer()
            );
        $productOfferTableDataTransfer->setPagination($paginationTransfer);

        return $productOfferTableDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function buildProductOfferTableBaseQuery(
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        $localeId = $productOfferTableCriteriaTransfer->getLocale()->getIdLocale();
        $merchantId = $productOfferTableCriteriaTransfer->getMerchantUser()->getIdMerchant();

        $productOfferQuery = $this->getFactory()->getProductOfferPropelQuery();

        $productOfferQuery = $this->joinProductLocalizedAttributesToProductOfferQuery($productOfferQuery, $localeId);
        $productOfferQuery = $this->joinProductAbstractLocalizedAttributesToProductOfferQuery($productOfferQuery, $localeId);
        $productOfferQuery->leftJoinSpyProductOfferValidity()
            ->leftJoinProductOfferStock()
            ->addAsColumn(ProductOfferTableRowDataTransfer::OFFER_REFERENCE, SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE)
            ->addAsColumn(ProductOfferTableRowDataTransfer::MERCHANT_SKU, SpyProductOfferTableMap::COL_MERCHANT_SKU)
            ->addAsColumn(ProductOfferTableRowDataTransfer::CONCRETE_SKU, SpyProductOfferTableMap::COL_CONCRETE_SKU)
            ->addAsColumn(ProductOfferTableRowDataTransfer::IMAGE, sprintf('(%s)', $this->createProductImagesSubquery($localeId)))
            ->addAsColumn(ProductOfferTableRowDataTransfer::STORES, sprintf('(%s)', $this->createProductOfferStoresSubquery()))
            ->addAsColumn(ProductOfferTableRowDataTransfer::APPROVAL_STATUS, SpyProductOfferTableMap::COL_APPROVAL_STATUS)
            ->addAsColumn(ProductOfferTableRowDataTransfer::QUANTITY, SpyProductOfferStockTableMap::COL_QUANTITY)
            ->addAsColumn(ProductOfferTableRowDataTransfer::IS_NEVER_OUT_OF_STOCK, SpyProductOfferStockTableMap::COL_IS_NEVER_OUT_OF_STOCK)
            ->addAsColumn(ProductOfferTableRowDataTransfer::IS_ACTIVE, SpyProductOfferTableMap::COL_IS_ACTIVE)
            ->addAsColumn(ProductOfferTableRowDataTransfer::PRODUCT_CONCRETE_NAME, SpyProductLocalizedAttributesTableMap::COL_NAME)
            ->addAsColumn(ProductOfferTableRowDataTransfer::PRODUCT_CONCRETE_ATTRIBUTES, SpyProductTableMap::COL_ATTRIBUTES)
            ->addAsColumn(ProductOfferTableRowDataTransfer::PRODUCT_ABSTRACT_ATTRIBUTES, SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES)
            ->addAsColumn(ProductOfferTableRowDataTransfer::PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES, SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES)
            ->addAsColumn(ProductOfferTableRowDataTransfer::PRODUCT_CONCRETE_LOCALIZED_ATTRIBUTES, SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES)
            ->addAsColumn(ProductOfferTableRowDataTransfer::VALID_FROM, SpyProductOfferValidityTableMap::COL_VALID_FROM)
            ->addAsColumn(ProductOfferTableRowDataTransfer::VALID_TO, SpyProductOfferValidityTableMap::COL_VALID_TO)
            ->addAsColumn(ProductOfferTableRowDataTransfer::CREATED_AT, SpyProductOfferTableMap::COL_CREATED_AT)
            ->addAsColumn(ProductOfferTableRowDataTransfer::UPDATED_AT, SpyProductOfferTableMap::COL_UPDATED_AT)
            ->filterByFkMerchant($merchantId)
            ->select([
                ProductOfferTableRowDataTransfer::OFFER_REFERENCE,
                ProductOfferTableRowDataTransfer::MERCHANT_SKU,
                ProductOfferTableRowDataTransfer::CONCRETE_SKU,
                ProductOfferTableRowDataTransfer::IMAGE,
                ProductOfferTableRowDataTransfer::STORES,
                ProductOfferTableRowDataTransfer::APPROVAL_STATUS,
                ProductOfferTableRowDataTransfer::QUANTITY,
                ProductOfferTableRowDataTransfer::IS_NEVER_OUT_OF_STOCK,
                ProductOfferTableRowDataTransfer::IS_ACTIVE,
                ProductOfferTableRowDataTransfer::PRODUCT_CONCRETE_NAME,
                ProductOfferTableRowDataTransfer::PRODUCT_CONCRETE_ATTRIBUTES,
                ProductOfferTableRowDataTransfer::PRODUCT_ABSTRACT_ATTRIBUTES,
                ProductOfferTableRowDataTransfer::VALID_FROM,
                ProductOfferTableRowDataTransfer::VALID_TO,
                ProductOfferTableRowDataTransfer::CREATED_AT,
                ProductOfferTableRowDataTransfer::UPDATED_AT,
            ]);

        return $productOfferQuery;
    }

    /**
     * @return string
     */
    protected function createProductOfferStoresSubquery(): string
    {
        $storesSubquery = $this->getFactory()->getProductOfferStorePropelQuery()
            ->joinSpyStore()
            ->addAsColumn('stores', sprintf('GROUP_CONCAT(DISTINCT %s)', SpyStoreTableMap::COL_NAME))
            ->where(sprintf(
                '%s = %s',
                SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER,
                SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER
            ));
        $params = [];

        return $storesSubquery->createSelectSql($params);
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param int $localeId
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function joinProductAbstractLocalizedAttributesToProductOfferQuery(SpyProductOfferQuery $productOfferQuery, int $localeId): SpyProductOfferQuery
    {
        $productAbstractLocalizedAttributesJoinName = 'productAbstractLocalizedAttributes';
        $productAbstractJoin = new Join(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, Criteria::INNER_JOIN);
        $productAbstractLocalizedAttributesJoin = new Join(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT, Criteria::INNER_JOIN);
        $productOfferQuery->addJoinObject($productAbstractJoin, 'productAbstract')
            ->addJoinObject($productAbstractLocalizedAttributesJoin, $productAbstractLocalizedAttributesJoinName)
            ->addJoinCondition(
                $productAbstractLocalizedAttributesJoinName,
                sprintf('%s = %s', SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE, $localeId)
            );

        return $productOfferQuery;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param int $localeId
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function joinProductLocalizedAttributesToProductOfferQuery(SpyProductOfferQuery $productOfferQuery, int $localeId): SpyProductOfferQuery
    {
        $productLocalizedAttributesJoinName = 'SpyProductLocalizedAttributes';
        $productJoin = new Join(SpyProductOfferTableMap::COL_CONCRETE_SKU, SpyProductTableMap::COL_SKU, Criteria::INNER_JOIN);
        $productLocalizedAttributesJoin = new Join(SpyProductTableMap::COL_ID_PRODUCT, SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT, Criteria::INNER_JOIN);

        $productOfferQuery->addJoinObject($productJoin, 'SpyProduct')
            ->addJoinObject($productLocalizedAttributesJoin, $productLocalizedAttributesJoinName)
            ->addJoinCondition(
                $productLocalizedAttributesJoinName,
                sprintf('%s = %s', SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE, $localeId)
            );

        return $productOfferQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     * @param string $searchTerm
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function getProductNameSearchCriteria(Criteria $criteria, string $searchTerm): AbstractCriterion
    {
        return $criteria->getNewCriterion(
            SpyProductLocalizedAttributesTableMap::COL_NAME,
            '%' . $searchTerm . '%',
            Criteria::LIKE
        );
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     * @param string $searchTerm
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function getProductSkuSearchCriteria(Criteria $criteria, string $searchTerm): AbstractCriterion
    {
        return $criteria->getNewCriterion(
            SpyProductTableMap::COL_SKU,
            '%' . $searchTerm . '%',
            Criteria::LIKE
        );
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     * @param string $searchTerm
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function getProductOfferReferenceSearchCriteria(Criteria $criteria, string $searchTerm): AbstractCriterion
    {
        return $criteria->getNewCriterion(
            SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE,
            '%' . $searchTerm . '%',
            Criteria::LIKE
        );
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     * @param string $searchTerm
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function getProductOfferMerchantSkuSearchCriteria(Criteria $criteria, string $searchTerm): AbstractCriterion
    {
        return $criteria->getNewCriterion(
            SpyProductOfferTableMap::COL_MERCHANT_SKU,
            '%' . $searchTerm . '%',
            Criteria::LIKE
        );
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function addProductOfferFilters(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        $productOfferQuery = $this->addIsVisibleProductOfferFilter($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addStockProductOfferFilter($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addStatusProductOfferFilter($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addStoresProductOfferFilter($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addValidityProductOfferFilter($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addCreationProductOfferFilter($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addUpdateProductOfferFilter($productOfferQuery, $productOfferTableCriteriaTransfer);

        return $productOfferQuery;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function addIsVisibleProductOfferFilter(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        if ($productOfferTableCriteriaTransfer->getIsVisible() === null) {
            return $productOfferQuery;
        }

        $productOfferQuery->filterByIsActive(
            $productOfferTableCriteriaTransfer->getIsVisible()
        );

        return $productOfferQuery;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function addStockProductOfferFilter(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        if ($productOfferTableCriteriaTransfer->getHasStock() === null) {
            return $productOfferQuery;
        }

        $productOfferQuery->where(
            sprintf(
                '(%s) %s 0',
                SpyProductOfferStockTableMap::COL_QUANTITY,
                $productOfferTableCriteriaTransfer->getHasStock() ? '>' : '='
            )
        );

        if (!$productOfferTableCriteriaTransfer->getHasStock()) {
            $productOfferQuery->_or()
                ->useProductOfferStockQuery()
                ->filterByIdProductOfferStock(null, Criteria::ISNULL)
                ->endUse();
        }

        return $productOfferQuery;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function addStatusProductOfferFilter(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        if ($productOfferTableCriteriaTransfer->getApprovalStatus() === null) {
            return $productOfferQuery;
        }

        $productOfferQuery->filterByApprovalStatus(
            $productOfferTableCriteriaTransfer->getApprovalStatus()
        );

        return $productOfferQuery;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function addStoresProductOfferFilter(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        if (!$productOfferTableCriteriaTransfer->getInStores()) {
            return $productOfferQuery;
        }

        $productOfferQuery->useSpyProductOfferStoreQuery()
            ->filterByFkStore_In($productOfferTableCriteriaTransfer->getInStores())
            ->endUse()
            ->distinct();

        return $productOfferQuery;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function addValidityProductOfferFilter(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        if ($productOfferTableCriteriaTransfer->getValidFrom()) {
            $productOfferQuery->useSpyProductOfferValidityQuery()
                ->filterByValidFrom($productOfferTableCriteriaTransfer->getValidFrom(), Criteria::GREATER_EQUAL)
                ->endUse();
        }

        if ($productOfferTableCriteriaTransfer->getValidTo()) {
            $productOfferQuery->useSpyProductOfferValidityQuery()
                ->filterByValidTo($productOfferTableCriteriaTransfer->getValidTo(), Criteria::LESS_EQUAL)
                ->endUse();
        }

        return $productOfferQuery;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function addCreationProductOfferFilter(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        if ($productOfferTableCriteriaTransfer->getCreatedFrom()) {
            $productOfferQuery->filterByCreatedAt($productOfferTableCriteriaTransfer->getCreatedFrom(), Criteria::GREATER_EQUAL);
        }

        if ($productOfferTableCriteriaTransfer->getCreatedTo()) {
            $productOfferQuery->filterByCreatedAt($productOfferTableCriteriaTransfer->getCreatedTo(), Criteria::LESS_EQUAL);
        }

        return $productOfferQuery;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function addUpdateProductOfferFilter(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        if ($productOfferTableCriteriaTransfer->getUpdatedFrom()) {
            $productOfferQuery->filterByUpdatedAt($productOfferTableCriteriaTransfer->getUpdatedFrom(), Criteria::GREATER_EQUAL);
        }

        if ($productOfferTableCriteriaTransfer->getUpdatedTo()) {
            $productOfferQuery->filterByUpdatedAt($productOfferTableCriteriaTransfer->getUpdatedTo(), Criteria::LESS_EQUAL);
        }

        return $productOfferQuery;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function addProductOfferSorting(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        if (!$productOfferTableCriteriaTransfer->getOrderBy()) {
            return $productOfferQuery;
        }

        foreach ($productOfferTableCriteriaTransfer->getOrderBy() as $field => $direction) {
            $sortField = ProductOfferTableDataMapper::PRODUCT_OFFER_DATA_COLUMN_MAP[$field] ?? $field;

            if (!$sortField) {
                continue;
            }

            $productOfferQuery->orderBy($sortField, $direction);
        }

        return $productOfferQuery;
    }
}
