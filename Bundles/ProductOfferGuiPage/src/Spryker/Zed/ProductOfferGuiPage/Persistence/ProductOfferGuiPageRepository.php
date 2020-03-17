<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableDataTransfer;
use Generated\Shared\Transfer\ProductTableRowDataTransfer;
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
use Spryker\Zed\ProductOfferGuiPage\Persistence\Propel\ProductTableDataMapper;

/**
 * @method \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPagePersistenceFactory getFactory()
 */
class ProductOfferGuiPageRepository extends AbstractRepository implements ProductOfferGuiPageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    public function getProductTableData(ProductTableCriteriaTransfer $productTableCriteriaTransfer): ProductTableDataTransfer
    {
        $productConcreteMapper = $this->getFactory()->createProductTableDataMapper();

        $productConcreteQuery = $this->buildBaseQuery($productTableCriteriaTransfer);
        $productConcreteQuery = $this->applySearch($productConcreteQuery, $productTableCriteriaTransfer);
        $productConcreteQuery = $this->addFilters($productConcreteQuery, $productTableCriteriaTransfer);
        $productConcreteQuery = $this->addSorting($productConcreteQuery, $productTableCriteriaTransfer);

        if (!$productTableCriteriaTransfer->getPagination()) {
            return $productConcreteMapper->mapProductTableDataArrayToTableDataTransfer(
                $productConcreteQuery->find()->getData(),
                new ProductTableDataTransfer()
            );
        }

        $paginationTransfer = $productTableCriteriaTransfer->getPagination();
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
     *
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function buildBaseQuery(ProductTableCriteriaTransfer $productTableCriteriaTransfer): SpyProductQuery
    {
        $productConcreteQuery = $this->getFactory()->getProductConcretePropelQuery();
        $localeId = $productTableCriteriaTransfer->requireLocale()
            ->getLocale()
            ->requireIdLocale()
            ->getIdLocale();

        $productConcreteQuery->joinSpyProductLocalizedAttributes()
            ->addJoinCondition(
                'SpyProductLocalizedAttributes',
                sprintf('%s = ?', SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE),
                $localeId
            )
            ->joinSpyProductAbstract()
            ->useSpyProductAbstractQuery()
                ->joinSpyProductAbstractStore()
                ->useSpyProductAbstractStoreQuery()
                    ->joinSpyStore()
                ->endUse()
            ->endUse()
            ->leftJoinSpyProductValidity()
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, ProductTableRowDataTransfer::ID_PRODUCT)
            ->withColumn(SpyProductTableMap::COL_SKU, ProductTableRowDataTransfer::SKU)
            ->withColumn(SpyProductTableMap::COL_ATTRIBUTES, ProductTableRowDataTransfer::ATTRIBUTES)
            ->withColumn(SpyProductTableMap::COL_IS_ACTIVE, ProductTableRowDataTransfer::IS_ACTIVE)
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, ProductTableRowDataTransfer::NAME)
            ->withColumn(SpyProductValidityTableMap::COL_VALID_FROM, ProductTableRowDataTransfer::VALID_FROM)
            ->withColumn(SpyProductValidityTableMap::COL_VALID_TO, ProductTableRowDataTransfer::VALID_TO)
            ->withColumn(sprintf('GROUP_CONCAT(DISTINCT %s)', SpyStoreTableMap::COL_NAME), ProductTableRowDataTransfer::STORES)
            ->select([
                ProductTableRowDataTransfer::ID_PRODUCT,
                ProductTableRowDataTransfer::SKU,
                ProductTableRowDataTransfer::ATTRIBUTES,
                ProductTableRowDataTransfer::IS_ACTIVE,
                ProductTableRowDataTransfer::NAME,
                ProductTableRowDataTransfer::VALID_FROM,
                ProductTableRowDataTransfer::VALID_TO,
                ProductTableRowDataTransfer::STORES,
            ])
            ->groupByIdProduct();

        return $productConcreteQuery;
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
            $sortField = ProductTableDataMapper::PRODUCT_DATA_COLUMN_MAP[$field] ?? null;

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
    protected function addHasOffersFilter(SpyProductQuery $productConcreteQuery, ProductTableCriteriaTransfer $productTableCriteriaTransfer): SpyProductQuery
    {
        $productConcreteHasOffers = $productTableCriteriaTransfer->getHasOffers() ?? null;
        $merchantUserTransfer = $productTableCriteriaTransfer->getMerchantUser();

        if ($productConcreteHasOffers === null || !$merchantUserTransfer) {
            return $productConcreteQuery;
        }

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
            ->having(
                sprintf(
                    '(COUNT(%s) %s 0) = FALSE',
                    SpyProductOfferTableMap::COL_CONCRETE_SKU,
                    $productConcreteHasOffers ? '>' : '='
                )
            );

        return $productConcreteQuery;
    }
}
