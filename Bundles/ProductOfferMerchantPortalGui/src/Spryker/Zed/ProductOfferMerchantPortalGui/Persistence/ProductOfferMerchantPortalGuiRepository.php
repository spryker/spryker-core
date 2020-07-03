<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Persistence;

use DateTime;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferValidityTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
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
    protected const OFFERS_COUNT_TOTAL = 'offersCountTotal';
    protected const OFFERS_COUNT_ACTIVE = 'offersCountActive';
    protected const OFFERS_COUNT_WITH_STOCK = 'offersCountWithStock';
    protected const OFFERS_COUNT_LOW_ON_STOCK = 'offersCountLowOnStock';
    protected const OFFERS_COUNT_VALID = 'offersCountValid';
    protected const OFFERS_COUNT_EXPIRING = 'offersCountExpiring';
    protected const OFFERS_COUNT_ON_MARKETPLACE = 'offersCountOnMarketplace';

    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductTableData(ProductTableCriteriaTransfer $productTableCriteriaTransfer): ProductConcreteCollectionTransfer
    {
        $productConcreteMapper = $this->getFactory()->createProductTableDataMapper();

        $productConcreteQuery = $this->buildProductTableBaseQuery($productTableCriteriaTransfer);
        $productConcreteQuery = $this->applyProductSearch($productConcreteQuery, $productTableCriteriaTransfer);
        $productConcreteQuery = $this->addProductFilters($productConcreteQuery, $productTableCriteriaTransfer);
        $productConcreteQuery = $this->addProductSorting($productConcreteQuery, $productTableCriteriaTransfer);

        $propelPager = $productConcreteQuery->paginate(
            $productTableCriteriaTransfer->requirePage()->getPage(),
            $productTableCriteriaTransfer->requirePageSize()->getPageSize()
        );

        $paginationTransfer = $this->hydratePaginationTransfer($propelPager);

        $productConcreteCollectionTransfer = $productConcreteMapper->mapProductTableDataArrayToProductConcreteCollectionTransfer(
            $propelPager->getResults()->getData(),
            new ProductConcreteCollectionTransfer()
        );
        $productConcreteCollectionTransfer->setPagination($paginationTransfer);

        return $productConcreteCollectionTransfer;
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
        $idLocale = $productTableCriteriaTransfer->requireIdLocale()->getIdLocale();
        $idMerchant = $productTableCriteriaTransfer->requireIdMerchant()->getIdMerchant();

        $productConcreteQuery = $this->addLocalizedAttributesToProductTableQuery($productConcreteQuery, $idLocale);
        $productConcreteQuery->leftJoinSpyProductValidity()
            ->addAsColumn(ProductConcreteTransfer::SKU, SpyProductTableMap::COL_SKU)
            ->addAsColumn(ProductConcreteTransfer::ATTRIBUTES, SpyProductTableMap::COL_ATTRIBUTES)
            ->addAsColumn(ProductConcreteTransfer::LOCALIZED_ATTRIBUTES, SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES)
            ->addAsColumn(ProductConcreteTransfer::IS_ACTIVE, SpyProductTableMap::COL_IS_ACTIVE)
            ->addAsColumn(LocalizedAttributesTransfer::NAME, SpyProductLocalizedAttributesTableMap::COL_NAME)
            ->addAsColumn(ProductConcreteTransfer::STORES, sprintf('(%s)', $this->createProductStoresSubquery()))
            ->addAsColumn(ProductImageTransfer::EXTERNAL_URL_SMALL, sprintf('(%s)', $this->createProductImagesSubquery($idLocale)))
            ->addAsColumn(ProductConcreteTransfer::NUMBER_OF_OFFERS, sprintf('(%s)', $this->createProductOffersCountSubquery($idMerchant)))
            ->addAsColumn(ProductConcreteTransfer::VALID_FROM, SpyProductValidityTableMap::COL_VALID_FROM)
            ->addAsColumn(ProductConcreteTransfer::VALID_TO, SpyProductValidityTableMap::COL_VALID_TO)
            ->where(sprintf('(%s) IS NOT NULL', $this->createProductStoresSubquery()))
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);

        return $productConcreteQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addLocalizedAttributesToProductTableQuery(SpyProductQuery $productConcreteQuery, int $idLocale): SpyProductQuery
    {
        $productConcreteQuery->joinSpyProductLocalizedAttributes()
            ->addJoinCondition(
                'SpyProductLocalizedAttributes',
                sprintf('%s = ?', SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE),
                $idLocale
            );

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
                SpyProductImageSetTableMap::COL_FK_PRODUCT,
                SpyProductTableMap::COL_ID_PRODUCT,
                SpyProductImageSetTableMap::COL_FK_LOCALE,
                $idLocale
            ))
            ->addSelectColumn(SpyProductImageTableMap::COL_EXTERNAL_URL_SMALL)
            ->orderBy(SpyProductImageSetTableMap::COL_FK_LOCALE)
            ->limit(1);
        $params = [];

        return $productImagesSubquery->createSelectSql($params);
    }

    /**
     * @param int $idMerchant
     *
     * @return string
     */
    protected function createProductOffersCountSubquery(int $idMerchant): string
    {
        $productOffersSubquery = $this->getFactory()->getProductOfferPropelQuery()
            ->addAsColumn('offers_count', 'COUNT(*)')
            ->where(sprintf(
                '%s = %s AND %s = %s',
                SpyProductOfferTableMap::COL_CONCRETE_SKU,
                SpyProductTableMap::COL_SKU,
                SpyProductOfferTableMap::COL_FK_MERCHANT,
                $idMerchant
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
        $orderColumn = $productTableCriteriaTransfer->getOrderBy();
        $orderDirection = $productTableCriteriaTransfer->getOrderDirection();

        if (!$orderColumn || !$orderDirection) {
            return $productConcreteQuery;
        }

        $orderColumn = ProductTableDataMapper::PRODUCT_DATA_COLUMN_MAP[$orderColumn] ?? $orderColumn;

        if ($orderColumn === SpyProductTableMap::COL_SKU) {
            $productConcreteQuery = $this->addNaturalSorting($productConcreteQuery, $orderColumn, $orderDirection);
        }

        $productConcreteQuery->orderBy($orderColumn, $orderDirection);

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
     * @param \Propel\Runtime\Util\PropelModelPager $propelPager
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function hydratePaginationTransfer(
        PropelModelPager $propelPager
    ): PaginationTransfer {
        return (new PaginationTransfer())
            ->setNbResults($propelPager->getNbResults())
            ->setPage($propelPager->getPage())
            ->setMaxPerPage($propelPager->getMaxPerPage())
            ->setFirstIndex($propelPager->getFirstIndex())
            ->setFirstIndex($propelPager->getFirstIndex())
            ->setLastIndex($propelPager->getLastIndex())
            ->setFirstPage($propelPager->getFirstPage())
            ->setLastPage($propelPager->getLastPage())
            ->setNextPage($propelPager->getNextPage())
            ->setPreviousPage($propelPager->getPreviousPage());
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addProductFilters(
        SpyProductQuery $productConcreteQuery,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): SpyProductQuery {
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
        $filterValue = $productTableCriteriaTransfer->getFilterIsActive();
        if (!isset($filterValue)) {
            return $productConcreteQuery;
        }

        $productConcreteQuery->filterByIsActive($filterValue);

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
        $productConcreteHasOffers = $productTableCriteriaTransfer->getFilterHasOffers();

        if ($productConcreteHasOffers === null) {
            return $productConcreteQuery;
        }

        $merchantUserId = $productTableCriteriaTransfer->requireIdMerchant()->getIdMerchant();
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
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferTableData(
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): ProductOfferCollectionTransfer {
        $productOfferQuery = $this->buildProductOfferTableBaseQuery($productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->applyProductOfferSearch($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addProductOfferFilters($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addProductOfferSorting($productOfferQuery, $productOfferTableCriteriaTransfer);

        $propelPager = $productOfferQuery->paginate(
            $productOfferTableCriteriaTransfer->requirePage()->getPage(),
            $productOfferTableCriteriaTransfer->requirePageSize()->getPageSize()
        );
        $paginationTransfer = $this->hydratePaginationTransfer($propelPager);

        $productOfferCollectionTransfer = $this->getFactory()
            ->createProductOfferTableDataMapper()
            ->mapProductOfferTableDataArrayToProductOfferCollectionTransfer(
                $propelPager->getResults()->getData(),
                new ProductOfferCollectionTransfer()
            );
        $productOfferCollectionTransfer->setPagination($paginationTransfer);

        return $productOfferCollectionTransfer;
    }

    /**
     * @module ProductOffer
     * @module MerchantProductOffer
     * @module ProductOfferValidity
     * @module ProductOfferStock
     * @module Product
     * @module ProductImage
     *
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function buildProductOfferTableBaseQuery(
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        $idLocale = $productOfferTableCriteriaTransfer->requireIdLocale()->getIdLocale();
        $idMerchant = $productOfferTableCriteriaTransfer->requireIdMerchant()->getIdMerchant();

        $productOfferQuery = $this->getFactory()->getProductOfferPropelQuery();

        $productOfferQuery = $this->joinProductLocalizedAttributesToProductOfferQuery($productOfferQuery, $idLocale);
        $productOfferQuery->leftJoinSpyProductOfferValidity()
            ->leftJoinProductOfferStock()
            ->addAsColumn(ProductOfferTransfer::PRODUCT_OFFER_REFERENCE, SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE)
            ->addAsColumn(ProductOfferTransfer::MERCHANT_SKU, SpyProductOfferTableMap::COL_MERCHANT_SKU)
            ->addAsColumn(ProductOfferTransfer::CONCRETE_SKU, SpyProductOfferTableMap::COL_CONCRETE_SKU)
            ->addAsColumn(ProductImageTransfer::EXTERNAL_URL_SMALL, sprintf('(%s)', $this->createProductImagesSubquery($idLocale)))
            ->addAsColumn(ProductOfferTransfer::STORES, sprintf('(%s)', $this->createProductOfferStoresSubquery()))
            ->addAsColumn(ProductOfferStockTransfer::QUANTITY, SpyProductOfferStockTableMap::COL_QUANTITY)
            ->addAsColumn(ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK, SpyProductOfferStockTableMap::COL_IS_NEVER_OUT_OF_STOCK)
            ->addAsColumn(ProductOfferTransfer::IS_ACTIVE, SpyProductOfferTableMap::COL_IS_ACTIVE)
            ->addAsColumn(LocalizedAttributesTransfer::NAME, SpyProductLocalizedAttributesTableMap::COL_NAME)
            ->addAsColumn(ProductOfferTransfer::PRODUCT_ATTRIBUTES, SpyProductTableMap::COL_ATTRIBUTES)
            ->addAsColumn(ProductOfferTransfer::PRODUCT_LOCALIZED_ATTRIBUTES, SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES)
            ->addAsColumn(ProductOfferValidityTransfer::VALID_FROM, SpyProductOfferValidityTableMap::COL_VALID_FROM)
            ->addAsColumn(ProductOfferValidityTransfer::VALID_TO, SpyProductOfferValidityTableMap::COL_VALID_TO)
            ->addAsColumn(ProductOfferTransfer::CREATED_AT, SpyProductOfferTableMap::COL_CREATED_AT)
            ->addAsColumn(ProductOfferTransfer::UPDATED_AT, SpyProductOfferTableMap::COL_UPDATED_AT)
            ->filterByFkMerchant($idMerchant)
            ->select([
                ProductOfferTransfer::PRODUCT_OFFER_REFERENCE,
                ProductOfferTransfer::MERCHANT_SKU,
                ProductOfferTransfer::CONCRETE_SKU,
                ProductImageTransfer::EXTERNAL_URL_SMALL,
                ProductOfferTransfer::STORES,
                ProductOfferStockTransfer::QUANTITY,
                ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK,
                ProductOfferTransfer::IS_ACTIVE,
                LocalizedAttributesTransfer::NAME,
                ProductOfferTransfer::PRODUCT_ATTRIBUTES,
                ProductOfferTransfer::PRODUCT_LOCALIZED_ATTRIBUTES,
                ProductOfferValidityTransfer::VALID_FROM,
                ProductOfferValidityTransfer::VALID_TO,
                ProductOfferTransfer::CREATED_AT,
                ProductOfferTransfer::UPDATED_AT,
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
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function joinProductLocalizedAttributesToProductOfferQuery(SpyProductOfferQuery $productOfferQuery, int $idLocale): SpyProductOfferQuery
    {
        $productLocalizedAttributesJoinName = 'SpyProductLocalizedAttributes';
        $productJoin = new Join(SpyProductOfferTableMap::COL_CONCRETE_SKU, SpyProductTableMap::COL_SKU, Criteria::INNER_JOIN);
        $productLocalizedAttributesJoin = new Join(SpyProductTableMap::COL_ID_PRODUCT, SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT, Criteria::INNER_JOIN);

        $productOfferQuery->addJoinObject($productJoin, 'SpyProduct')
            ->addJoinObject($productLocalizedAttributesJoin, $productLocalizedAttributesJoinName)
            ->addJoinCondition(
                $productLocalizedAttributesJoinName,
                sprintf('%s = %s', SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE, $idLocale)
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
        $productOfferQuery = $this->addIsActiveProductOfferFilter($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addStockProductOfferFilter($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addStoreProductOfferFilter($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addValidityProductOfferFilter($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addCreatedAtProductOfferFilter($productOfferQuery, $productOfferTableCriteriaTransfer);
        $productOfferQuery = $this->addUpdatedAtProductOfferFilter($productOfferQuery, $productOfferTableCriteriaTransfer);

        return $productOfferQuery;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function addIsActiveProductOfferFilter(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        if ($productOfferTableCriteriaTransfer->getFilterIsActive() === null) {
            return $productOfferQuery;
        }

        $productOfferQuery->filterByIsActive(
            $productOfferTableCriteriaTransfer->getFilterIsActive()
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
        if ($productOfferTableCriteriaTransfer->getFilterHasStock() === null) {
            return $productOfferQuery;
        }

        $productOfferQuery->where(
            sprintf(
                '(%s) %s 0',
                SpyProductOfferStockTableMap::COL_QUANTITY,
                $productOfferTableCriteriaTransfer->getFilterHasStock() ? '>' : '='
            )
        );

        if (!$productOfferTableCriteriaTransfer->getFilterHasStock()) {
            $productOfferQuery->_or()
                ->useProductOfferStockQuery(null, Criteria::LEFT_JOIN)
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
    protected function addStoreProductOfferFilter(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        if (!$productOfferTableCriteriaTransfer->getFilterInStores()) {
            return $productOfferQuery;
        }

        $productOfferQuery->useSpyProductOfferStoreQuery()
            ->filterByFkStore_In($productOfferTableCriteriaTransfer->getFilterInStores())
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
        $criteriaRangeFilterTransfer = $productOfferTableCriteriaTransfer->getFilterValidity();

        if (!$criteriaRangeFilterTransfer) {
            return $productOfferQuery;
        }

        if ($criteriaRangeFilterTransfer->getFrom()) {
            $productOfferQuery->useSpyProductOfferValidityQuery()
                ->filterByValidFrom($criteriaRangeFilterTransfer->getFrom(), Criteria::GREATER_EQUAL)
                ->endUse();
        }

        if ($criteriaRangeFilterTransfer->getTo()) {
            $productOfferQuery->useSpyProductOfferValidityQuery()
                ->filterByValidTo($criteriaRangeFilterTransfer->getTo(), Criteria::LESS_THAN)
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
    protected function addCreatedAtProductOfferFilter(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        $criteriaRangeFilterTransfer = $productOfferTableCriteriaTransfer->getFilterCreatedAt();

        if (!$criteriaRangeFilterTransfer) {
            return $productOfferQuery;
        }

        if ($criteriaRangeFilterTransfer->getFrom()) {
            $productOfferQuery->filterByCreatedAt($criteriaRangeFilterTransfer->getFrom(), Criteria::GREATER_EQUAL);
        }

        if ($criteriaRangeFilterTransfer->getTo()) {
            $productOfferQuery->filterByCreatedAt(
                $criteriaRangeFilterTransfer->getTo(),
                Criteria::LESS_THAN
            );
        }

        return $productOfferQuery;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function addUpdatedAtProductOfferFilter(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): SpyProductOfferQuery {
        $criteriaRangeFilterTransfer = $productOfferTableCriteriaTransfer->getFilterUpdatedAt();

        if (!$criteriaRangeFilterTransfer) {
            return $productOfferQuery;
        }

        if ($criteriaRangeFilterTransfer->getFrom()) {
            $productOfferQuery->filterByUpdatedAt($criteriaRangeFilterTransfer->getFrom(), Criteria::GREATER_EQUAL);
        }

        if ($criteriaRangeFilterTransfer->getTo()) {
            $productOfferQuery->filterByUpdatedAt(
                $criteriaRangeFilterTransfer->getTo(),
                Criteria::LESS_THAN
            );
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
        $orderColumn = $productOfferTableCriteriaTransfer->getOrderBy();
        $orderDirection = $productOfferTableCriteriaTransfer->getOrderDirection();

        if (!$orderColumn || !$orderDirection) {
            return $productOfferQuery;
        }

        $orderColumn = ProductOfferTableDataMapper::PRODUCT_OFFER_DATA_COLUMN_MAP[$orderColumn] ?? $orderColumn;

        if (
            in_array($orderColumn, [
            SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE,
            SpyProductOfferTableMap::COL_MERCHANT_SKU,
            SpyProductOfferTableMap::COL_CONCRETE_SKU,
            ], true)
        ) {
            $productOfferQuery = $this->addNaturalSorting($productOfferQuery, $orderColumn, $orderDirection);
        }
        $productOfferQuery->orderBy($orderColumn, $orderDirection);

        return $productOfferQuery;
    }

    /**
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

        return $query;
    }

    /**
     * @module ProductOffer
     * @module ProductOfferStock
     * @module ProductOfferValidity
     *
     * @param int $idMerchant
     *
     * @return int[]
     */
    public function getOffersDashboardCardCounts(int $idMerchant): array
    {
        $productOfferMerchantPortalGuiConfig = $this->getFactory()->getConfig();
        $dashboardExpiringOffersLimit = $productOfferMerchantPortalGuiConfig->getDashboardExpiringOffersLimit();
        $dashboardLowStockThreshold = $productOfferMerchantPortalGuiConfig->getDashboardLowStockThreshold();
        $currentDateTime = (new DateTime())->format('Y-m-d H:i:s');
        $expiringOffersDateTime = (new DateTime(sprintf('+%s Days', $dashboardExpiringOffersLimit)))->format('Y-m-d H:i:s');

        return $this->getFactory()->getProductOfferPropelQuery()
            ->leftJoinSpyProductOfferValidity()
            ->leftJoinProductOfferStock()
            ->filterByFkMerchant($idMerchant)
            ->addAsColumn(static::OFFERS_COUNT_TOTAL, 'COUNT(*)')
            ->addAsColumn(
                static::OFFERS_COUNT_ACTIVE,
                'COUNT(CASE WHEN ' . SpyProductOfferTableMap::COL_IS_ACTIVE . ' IS TRUE THEN 1 END)'
            )
            ->addAsColumn(
                static::OFFERS_COUNT_WITH_STOCK,
                'COUNT(CASE WHEN ' . SpyProductOfferStockTableMap::COL_QUANTITY . ' > 0 THEN 1 END)'
            )
            ->addAsColumn(
                static::OFFERS_COUNT_LOW_ON_STOCK,
                'COUNT(CASE WHEN ' . SpyProductOfferStockTableMap::COL_QUANTITY . " < $dashboardLowStockThreshold THEN 1 END)"
            )
            ->addAsColumn(
                static::OFFERS_COUNT_VALID,
                "COUNT(CASE WHEN '$currentDateTime' BETWEEN " .
                    SpyProductOfferValidityTableMap::COL_VALID_FROM . ' AND ' . SpyProductOfferValidityTableMap::COL_VALID_TO .
                    ' OR ' . SpyProductOfferValidityTableMap::COL_ID_PRODUCT_OFFER_VALIDITY . ' IS NULL THEN 1 END)'
            )
            ->addAsColumn(
                static::OFFERS_COUNT_EXPIRING,
                "COUNT(CASE WHEN '$currentDateTime' < " . SpyProductOfferValidityTableMap::COL_VALID_TO
                . " AND '$expiringOffersDateTime' > " . SpyProductOfferValidityTableMap::COL_VALID_TO . ' THEN 1 END)'
            )
            ->addAsColumn(
                static::OFFERS_COUNT_ON_MARKETPLACE,
                'COUNT(CASE WHEN ' . SpyProductOfferTableMap::COL_IS_ACTIVE . " IS TRUE
                    AND " . SpyProductOfferStockTableMap::COL_QUANTITY . " > 0 AND (
                    '$currentDateTime' BETWEEN " . SpyProductOfferValidityTableMap::COL_VALID_FROM . ' AND ' . SpyProductOfferValidityTableMap::COL_VALID_TO .
                        ' OR ' . SpyProductOfferValidityTableMap::COL_ID_PRODUCT_OFFER_VALIDITY . " IS NULL
                    ) THEN 1 END)"
            )
            ->select([
                static::OFFERS_COUNT_TOTAL,
                static::OFFERS_COUNT_ACTIVE,
                static::OFFERS_COUNT_WITH_STOCK,
                static::OFFERS_COUNT_LOW_ON_STOCK,
                static::OFFERS_COUNT_VALID,
                static::OFFERS_COUNT_EXPIRING,
                static::OFFERS_COUNT_ON_MARKETPLACE,
            ])
            ->findOne();
    }
}
