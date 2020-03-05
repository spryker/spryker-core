<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductListTableCriteriaTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductValidity\Persistence\Map\SpyProductValidityTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Util\PropelModelPager;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPagePersistenceFactory getFactory()
 */
class ProductOfferGuiPageRepository extends AbstractRepository implements ProductOfferGuiPageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListTableCriteriaTransfer $productListTableCriteriaTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\MerchantUserTransfer|null $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getConcreteProductsForProductListTable(
        ProductListTableCriteriaTransfer $productListTableCriteriaTransfer,
        LocaleTransfer $localeTransfer,
        ?MerchantUserTransfer $merchantUserTransfer
    ): ProductConcreteCollectionTransfer {
        $productConcreteMapper = $this->getFactory()->createProductConcreteMapper();

        $productConcreteQuery = $this->buildBaseQuery($localeTransfer, $merchantUserTransfer);
        $productConcreteQuery = $this->applySearch($productConcreteQuery, $productListTableCriteriaTransfer);
        $productConcreteQuery = $this->addFilters($productConcreteQuery, $productListTableCriteriaTransfer);
        $productConcreteQuery = $this->addSorting($productConcreteQuery, $productListTableCriteriaTransfer);

        if (!$productListTableCriteriaTransfer->getPagination()) {
            return $productConcreteMapper->mapProductConcreteEntitiesToProductConcreteCollectionTransfer(
                $productConcreteQuery->find(),
                new ProductConcreteCollectionTransfer()
            );
        }

        $paginationTransfer = $productListTableCriteriaTransfer->getPagination();
        $pager = $this->getPagerForQuery($productConcreteQuery, $paginationTransfer);
        $paginationTransfer = $this->hydratePaginationTransfer($paginationTransfer, $pager);

        return $productConcreteMapper->mapProductConcreteEntitiesToProductConcreteCollectionTransfer(
            $pager->getResults(),
            new ProductConcreteCollectionTransfer()
        )
            ->setPagination($paginationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\MerchantUserTransfer|null $merchantUserTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function buildBaseQuery(
        LocaleTransfer $localeTransfer,
        ?MerchantUserTransfer $merchantUserTransfer
    ): SpyProductQuery {
        $productConcreteQuery = $this->getFactory()->getProductConcretePropelQuery();

        $productConcreteQuery->joinSpyProductLocalizedAttributes()
            ->addJoinCondition(
                'SpyProductLocalizedAttributes',
                sprintf('%s = ?', SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE),
                $localeTransfer->getIdLocale()
            )
            ->joinSpyProductAbstract()
            ->useSpyProductAbstractQuery()
            ->joinSpyProductAbstractStore()
            ->useSpyProductAbstractStoreQuery()
            ->joinSpyStore()
            ->endUse()
            ->endUse();

        if ($merchantUserTransfer) {
            $productConcreteQuery->addJoin(
                [
                    SpyProductTableMap::COL_SKU,
                    SpyProductOfferTableMap::COL_FK_MERCHANT,
                ],
                [
                    SpyProductOfferTableMap::COL_CONCRETE_SKU,
                    $merchantUserTransfer->getIdMerchant(),
                ],
                Criteria::LEFT_JOIN
            )
                ->withColumn('COUNT(' . SpyProductOfferTableMap::COL_CONCRETE_SKU . ') > 0', ProductConcreteTransfer::HAS_OFFERS);
        }

        $productConcreteQuery->leftJoinSpyProductValidity()
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, ProductConcreteTransfer::NAME)
            ->withColumn(SpyProductValidityTableMap::COL_VALID_FROM, ProductConcreteTransfer::VALID_FROM)
            ->withColumn(SpyProductValidityTableMap::COL_VALID_TO, ProductConcreteTransfer::VALID_TO)
            ->withColumn('GROUP_CONCAT(' . SpyStoreTableMap::COL_NAME . ')', ProductConcreteTransfer::STORE_NAMES)
            ->groupByIdProduct();

        return $productConcreteQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductListTableCriteriaTransfer $productListTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addSorting(
        SpyProductQuery $productConcreteQuery,
        ProductListTableCriteriaTransfer $productListTableCriteriaTransfer
    ): SpyProductQuery {
        if (!$productListTableCriteriaTransfer->getOrderBy()) {
            return $productConcreteQuery;
        }

        foreach ($productListTableCriteriaTransfer->getOrderBy() as $field => $direction) {
            $productConcreteQuery->orderBy($field, $direction);
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
     * @param \Propel\Runtime\Util\PropelModelPager $pager
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function hydratePaginationTransfer(
        PaginationTransfer $paginationTransfer,
        PropelModelPager $pager
    ): PaginationTransfer {
        $paginationTransfer->setNbResults($pager->getNbResults())
            ->setFirstIndex($pager->getFirstIndex())
            ->setLastIndex($pager->getLastIndex())
            ->setFirstPage($pager->getFirstPage())
            ->setLastPage($pager->getLastPage())
            ->setNextPage($pager->getNextPage())
            ->setPreviousPage($pager->getPreviousPage());

        return $paginationTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductListTableCriteriaTransfer $productListTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addFilters(SpyProductQuery $productConcreteQuery, ProductListTableCriteriaTransfer $productListTableCriteriaTransfer): SpyProductQuery
    {
        $productConcreteQuery = $this->addIsActiveFilter($productConcreteQuery, $productListTableCriteriaTransfer);
        $productConcreteQuery = $this->addInStoresFilter($productConcreteQuery, $productListTableCriteriaTransfer);
        $productConcreteQuery = $this->addInCategoriesFilter($productConcreteQuery, $productListTableCriteriaTransfer);

        return $productConcreteQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductListTableCriteriaTransfer $productListTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function applySearch(SpyProductQuery $productConcreteQuery, ProductListTableCriteriaTransfer $productListTableCriteriaTransfer): SpyProductQuery
    {
        $searchTerm = $productListTableCriteriaTransfer->getSearchTerm();

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
     * @param \Generated\Shared\Transfer\ProductListTableCriteriaTransfer $productListTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addIsActiveFilter(SpyProductQuery $productConcreteQuery, ProductListTableCriteriaTransfer $productListTableCriteriaTransfer): SpyProductQuery
    {
        if ($productListTableCriteriaTransfer->getIsActive() === null) {
            return $productConcreteQuery;
        }

        return $productConcreteQuery->filterByIsActive(
            $productListTableCriteriaTransfer->getIsActive()
        );
    }

    /**
     * @module Store
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductListTableCriteriaTransfer$productListTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addInStoresFilter(SpyProductQuery $productConcreteQuery, ProductListTableCriteriaTransfer $productListTableCriteriaTransfer): SpyProductQuery
    {
        if (!$productListTableCriteriaTransfer->getInStores()) {
            return $productConcreteQuery;
        }

        $productConcreteQuery->useSpyProductAbstractQuery()
                ->joinSpyProductAbstractStore()
                ->useSpyProductAbstractStoreQuery()
                    ->joinSpyStore()
                    ->useSpyStoreQuery()
                        ->filterByName_In($productListTableCriteriaTransfer->getInStores())
                    ->endUse()
                ->endUse()
            ->endUse();

        return $productConcreteQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productConcreteQuery
     * @param \Generated\Shared\Transfer\ProductListTableCriteriaTransfer $productListTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addInCategoriesFilter(SpyProductQuery $productConcreteQuery, ProductListTableCriteriaTransfer $productListTableCriteriaTransfer): SpyProductQuery
    {
        if (!$productListTableCriteriaTransfer->getInCategories()) {
            return $productConcreteQuery;
        }

        $productConcreteQuery->joinSpyProductAbstract()
            ->useSpyProductAbstractQuery()
                ->joinSpyProductCategory()
                ->useSpyProductCategoryQuery()
                    ->joinSpyCategory()
                    ->useSpyCategoryQuery()
                        ->joinAttribute()
                        ->useAttributeQuery()
                            ->filterByName_In($productListTableCriteriaTransfer->getInCategories())
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse();

        return $productConcreteQuery;
    }
}
