<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderItemTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTotalsTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineItemStateTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Util\PropelModelPager;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\SalesMerchantPortalGui\Persistence\Propel\Mapper\SalesMerchantPortalGuiMapper;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiPersistenceFactory getFactory()
 */
class SalesMerchantPortalGuiRepository extends AbstractRepository implements SalesMerchantPortalGuiRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function getMerchantOrderTableData(
        MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
    ): MerchantOrderCollectionTransfer {
        $merchantSalesOrderQuery = $this->buildMerchantOrderTableBaseQuery($merchantOrderTableCriteriaTransfer);
        $merchantSalesOrderQuery = $this->applyMerchantOrderSearch($merchantSalesOrderQuery, $merchantOrderTableCriteriaTransfer);
        $merchantSalesOrderQuery = $this->addMerchantOrderSorting($merchantSalesOrderQuery, $merchantOrderTableCriteriaTransfer);
        $merchantSalesOrderQuery = $this->addMerchantOrderFilters($merchantSalesOrderQuery, $merchantOrderTableCriteriaTransfer);

        $propelPager = $merchantSalesOrderQuery->paginate(
            $merchantOrderTableCriteriaTransfer->requirePage()->getPage(),
            $merchantOrderTableCriteriaTransfer->requirePageSize()->getPageSize()
        );

        $paginationTransfer = $this->hydratePaginationTransfer($propelPager);

        $merchantOrderCollectionTransfer = $this->getFactory()
            ->createSalesMerchantPortalGuiMapper()
            ->mapMerchantOrderTableDataArrayToMerchantOrderCollectionTransfer(
                $propelPager->getResults()->getData(),
                new MerchantOrderCollectionTransfer()
            );

        $merchantOrderCollectionTransfer->setPagination($paginationTransfer);

        return $merchantOrderCollectionTransfer;
    }

    /**
     * @module Merchant
     * @module MerchantOms
     * @module MerchantSalesOrder
     * @module Sales
     * @module StateMachine
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function buildMerchantOrderTableBaseQuery(
        MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
    ): SpyMerchantSalesOrderQuery {
        $idMerchant = $merchantOrderTableCriteriaTransfer->requireIdMerchant()->getIdMerchant();

        $merchantSalesOrderQuery = $this->getFactory()->getMerchantSalesOrderPropelQuery();
        $merchantSalesOrderQuery = $this->filterMerchantSalesOrderQueryByIdMerchant($merchantSalesOrderQuery, $idMerchant);
        $merchantSalesOrderQuery->joinMerchantSalesOrderItem()
            ->leftJoinWithOrder()
            ->useMerchantSalesOrderItemQuery(null, Criteria::JOIN)
                ->leftJoinStateMachineItemState()
            ->endUse()
            ->joinMerchantSalesOrderTotal()
            ->addAsColumn(MerchantOrderTransfer::MERCHANT_ORDER_ITEM_COUNT, sprintf('COUNT(%s)', SpyMerchantSalesOrderItemTableMap::COL_ID_MERCHANT_SALES_ORDER_ITEM))
            ->addAsColumn(OrderTransfer::ORDER_REFERENCE, SpySalesOrderTableMap::COL_ORDER_REFERENCE)
            ->addAsColumn(MerchantOrderTransfer::MERCHANT_ORDER_REFERENCE, SpyMerchantSalesOrderTableMap::COL_MERCHANT_SALES_ORDER_REFERENCE)
            ->addAsColumn(MerchantOrderTransfer::CREATED_AT, SpyMerchantSalesOrderTableMap::COL_CREATED_AT)
            ->addAsColumn(OrderTransfer::FIRST_NAME, SpySalesOrderTableMap::COL_FIRST_NAME)
            ->addAsColumn(OrderTransfer::LAST_NAME, SpySalesOrderTableMap::COL_LAST_NAME)
            ->addAsColumn(OrderTransfer::SALUTATION, SpySalesOrderTableMap::COL_SALUTATION)
            ->addAsColumn(OrderTransfer::EMAIL, SpySalesOrderTableMap::COL_EMAIL)
            ->addAsColumn(MerchantOrderTransfer::ITEM_STATES, sprintf('GROUP_CONCAT(DISTINCT %s)', SpyStateMachineItemStateTableMap::COL_NAME))
            ->addAsColumn(TotalsTransfer::GRAND_TOTAL, SpyMerchantSalesOrderTotalsTableMap::COL_GRAND_TOTAL)
            ->addAsColumn(OrderTransfer::STORE, SpySalesOrderTableMap::COL_STORE)
            ->addAsColumn(OrderTransfer::CURRENCY_ISO_CODE, SpySalesOrderTableMap::COL_CURRENCY_ISO_CODE)
            ->groupByIdMerchantSalesOrder()
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);

        return $merchantSalesOrderQuery;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param int $idMerchant
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function filterMerchantSalesOrderQueryByIdMerchant(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        int $idMerchant
    ): SpyMerchantSalesOrderQuery {
        $merchantSalesOrderQuery->addJoin(
            SpyMerchantSalesOrderTableMap::COL_MERCHANT_REFERENCE,
            SpyMerchantTableMap::COL_MERCHANT_REFERENCE,
            Criteria::INNER_JOIN
        );
        $merchantSalesOrderQuery->addAnd(
            SpyMerchantTableMap::COL_ID_MERCHANT,
            $idMerchant
        );

        return $merchantSalesOrderQuery;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param \Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function applyMerchantOrderSearch(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
    ): SpyMerchantSalesOrderQuery {
        $searchTerm = $merchantOrderTableCriteriaTransfer->getSearchTerm();

        if (!$searchTerm) {
            return $merchantSalesOrderQuery;
        }

        $criteria = new Criteria();
        $orderReferenceSearchCriteria = $this->getOrderReferenceSearchCriteria($criteria, $searchTerm);
        $merchantOrderReferenceSearchCriteria = $this->getMerchantOrderReferenceSearchCriteria($criteria, $searchTerm);
        $orderFirstNameSearchCriteria = $this->getOrderFirstNameSearchCriteria($criteria, $searchTerm);
        $orderLastNameSearchCriteria = $this->getOrderLastNameSearchCriteria($criteria, $searchTerm);
        $orderEmailSearchCriteria = $this->getOrderEmailSearchCriteria($criteria, $searchTerm);

        $orderReferenceSearchCriteria->addOr($merchantOrderReferenceSearchCriteria);
        $merchantOrderReferenceSearchCriteria->addOr($orderFirstNameSearchCriteria);
        $orderFirstNameSearchCriteria->addOr($orderLastNameSearchCriteria);
        $orderLastNameSearchCriteria->addOr($orderEmailSearchCriteria);

        $merchantSalesOrderQuery->setIgnoreCase(true);

        return $merchantSalesOrderQuery->add($orderReferenceSearchCriteria);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     * @param string $searchTerm
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function getOrderReferenceSearchCriteria(Criteria $criteria, string $searchTerm): AbstractCriterion
    {
        return $criteria->getNewCriterion(
            SpySalesOrderTableMap::COL_ORDER_REFERENCE,
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
    protected function getMerchantOrderReferenceSearchCriteria(Criteria $criteria, string $searchTerm): AbstractCriterion
    {
        return $criteria->getNewCriterion(
            SpyMerchantSalesOrderTableMap::COL_MERCHANT_SALES_ORDER_REFERENCE,
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
    protected function getOrderFirstNameSearchCriteria(Criteria $criteria, string $searchTerm): AbstractCriterion
    {
        return $criteria->getNewCriterion(
            SpySalesOrderTableMap::COL_FIRST_NAME,
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
    protected function getOrderLastNameSearchCriteria(Criteria $criteria, string $searchTerm): AbstractCriterion
    {
        return $criteria->getNewCriterion(
            SpySalesOrderTableMap::COL_LAST_NAME,
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
    protected function getOrderEmailSearchCriteria(Criteria $criteria, string $searchTerm): AbstractCriterion
    {
        return $criteria->getNewCriterion(
            SpySalesOrderTableMap::COL_EMAIL,
            '%' . $searchTerm . '%',
            Criteria::LIKE
        );
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param \Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function addMerchantOrderSorting(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
    ): SpyMerchantSalesOrderQuery {
        $orderColumn = $merchantOrderTableCriteriaTransfer->getOrderBy();
        $orderDirection = $merchantOrderTableCriteriaTransfer->getOrderDirection();

        if (!$orderColumn || !$orderDirection) {
            return $merchantSalesOrderQuery;
        }

        $orderColumn = SalesMerchantPortalGuiMapper::MERCHANT_ORDER_DATA_COLUMN_MAP[$orderColumn] ?? $orderColumn;

        if (
            in_array($orderColumn, [
                SpySalesOrderTableMap::COL_ORDER_REFERENCE,
                SpyMerchantSalesOrderTableMap::COL_MERCHANT_SALES_ORDER_REFERENCE,
            ], true)
        ) {
            $merchantSalesOrderQuery = $this->addNaturalSorting($merchantSalesOrderQuery, $orderColumn, $orderDirection);
        }

        $merchantSalesOrderQuery->orderBy($orderColumn, $orderDirection);

        return $merchantSalesOrderQuery;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param \Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function addMerchantOrderFilters(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
    ): SpyMerchantSalesOrderQuery {
        $merchantSalesOrderQuery = $this->addStoreMerchantOrderFilter($merchantSalesOrderQuery, $merchantOrderTableCriteriaTransfer);
        $merchantSalesOrderQuery = $this->addCreatedMerchantOrderFilter($merchantSalesOrderQuery, $merchantOrderTableCriteriaTransfer);
        $merchantSalesOrderQuery = $this->addOrderItemStatesMerchantOrderFilter($merchantSalesOrderQuery, $merchantOrderTableCriteriaTransfer);

        return $merchantSalesOrderQuery;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param \Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function addCreatedMerchantOrderFilter(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
    ): SpyMerchantSalesOrderQuery {
        $criteriaRangeFilterTransfer = $merchantOrderTableCriteriaTransfer->getFilterCreated();

        if (!$criteriaRangeFilterTransfer) {
            return $merchantSalesOrderQuery;
        }

        if ($criteriaRangeFilterTransfer->getFrom()) {
            $merchantSalesOrderQuery->filterByCreatedAt($criteriaRangeFilterTransfer->getFrom(), Criteria::GREATER_EQUAL);
        }

        if ($criteriaRangeFilterTransfer->getTo()) {
            $merchantSalesOrderQuery->filterByCreatedAt($criteriaRangeFilterTransfer->getTo(), Criteria::LESS_THAN);
        }

        return $merchantSalesOrderQuery;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param \Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function addStoreMerchantOrderFilter(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
    ): SpyMerchantSalesOrderQuery {
        if (!$merchantOrderTableCriteriaTransfer->getFilterStores()) {
            return $merchantSalesOrderQuery;
        }

        $merchantSalesOrderQuery->useOrderQuery()
                ->filterByStore_In($merchantOrderTableCriteriaTransfer->getFilterStores())
            ->endUse();

        return $merchantSalesOrderQuery;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param \Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function addOrderItemStatesMerchantOrderFilter(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
    ): SpyMerchantSalesOrderQuery {
        if (!$merchantOrderTableCriteriaTransfer->getFilterOrderItemStates()) {
            return $merchantSalesOrderQuery;
        }

        $merchantSalesOrderQuery->useMerchantSalesOrderItemQuery(null, Criteria::JOIN)
                ->useStateMachineItemStateQuery()
                    ->filterByName_In($merchantOrderTableCriteriaTransfer->getFilterOrderItemStates())
                ->endUse()
            ->endUse();

        return $merchantSalesOrderQuery;
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
}
