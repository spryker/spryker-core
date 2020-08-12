<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Persistence;

use DateTime;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderCountsTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderItemTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTotalsTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemMetadataTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineItemStateTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\LikeCriterion;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Util\PropelModelPager;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\SalesMerchantPortalGui\Persistence\Propel\Mapper\MerchantOrderItemTableDataMapper;
use Spryker\Zed\SalesMerchantPortalGui\Persistence\Propel\Mapper\MerchantOrderTableDataMapper;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiPersistenceFactory getFactory()
 */
class SalesMerchantPortalGuiRepository extends AbstractRepository implements SalesMerchantPortalGuiRepositoryInterface
{
    /**
     * @uses \Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderGuiTableConfigurationProvider::COL_KEY_REFERENCE
     */
    public const COL_KEY_REFERENCE = 'reference';

    /**
     * @uses \Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderItemGuiTableConfigurationProvider::COL_KEY_SKU
     */
    protected const COL_KEY_SKU = 'sku';

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
            ->createMerchantOrderTableDataMapper()
            ->mapMerchantOrderTableDataArrayToMerchantOrderCollectionTransfer(
                $propelPager->getResults()->getData(),
                new MerchantOrderCollectionTransfer()
            );

        $merchantOrderCollectionTransfer->setPagination($paginationTransfer);

        return $merchantOrderCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer
     */
    public function getMerchantOrderItemTableData(
        MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
    ): MerchantOrderItemCollectionTransfer {
        $merchantSalesOrderItemQuery = $this->buildMerchantOrderItemTableBaseQuery($merchantOrderItemTableCriteriaTransfer);
        $merchantSalesOrderItemQuery = $this->addMerchantOrderItemSorting($merchantSalesOrderItemQuery, $merchantOrderItemTableCriteriaTransfer);
        $merchantSalesOrderItemQuery = $this->applyMerchantOrderItemSearch($merchantSalesOrderItemQuery, $merchantOrderItemTableCriteriaTransfer);
        $merchantSalesOrderItemQuery = $this->addMerchantOrderItemFilters($merchantSalesOrderItemQuery, $merchantOrderItemTableCriteriaTransfer);

        $propelPager = $merchantSalesOrderItemQuery->paginate(
            $merchantOrderItemTableCriteriaTransfer->requirePage()->getPage(),
            $merchantOrderItemTableCriteriaTransfer->requirePageSize()->getPageSize()
        );

        $paginationTransfer = $this->hydratePaginationTransfer($propelPager);

        $merchantOrderItemCollectionTransfer = $this->getFactory()
            ->createMerchantOrderItemTableDataMapper()
            ->mapMerchantOrderItemTableDataArrayToMerchantOrderCollectionTransfer(
                $propelPager->getResults()->getData(),
                new MerchantOrderItemCollectionTransfer()
            );

        $merchantOrderItemCollectionTransfer->setPagination($paginationTransfer);

        return $merchantOrderItemCollectionTransfer;
    }

    /**
     * @module Merchant
     * @module MerchantSalesOrder
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCountsTransfer
     */
    public function getMerchantOrderCounts(int $idMerchant): MerchantOrderCountsTransfer
    {
        $salesMerchantPortalGuiConfig = $this->getFactory()->getConfig();
        $newOrdersDaysThreshold = $salesMerchantPortalGuiConfig->getDashboardNewOrdersDaysThreshold();
        $newOrdersDateTime = (new DateTime(sprintf('-%s Days', $newOrdersDaysThreshold)))->format('Y-m-d H:i:s');

        $merchantSalesOrderQuery = $this->getFactory()->getMerchantSalesOrderPropelQuery();
        $merchantSalesOrderQuery = $this->filterMerchantSalesOrderQueryByIdMerchant($merchantSalesOrderQuery, $idMerchant);

        /** @var array $merchantOrderCounts */
        $merchantOrderCounts = $merchantSalesOrderQuery
            ->addAsColumn(MerchantOrderCountsTransfer::TOTAL, 'COUNT(*)')
            ->addAsColumn(
                MerchantOrderCountsTransfer::NEW,
                "COUNT(CASE WHEN '" . $newOrdersDateTime . "' < " . SpyMerchantSalesOrderTableMap::COL_CREATED_AT . ' THEN 1 END)'
            )
            ->select([
                MerchantOrderCountsTransfer::TOTAL,
                MerchantOrderCountsTransfer::NEW,
            ])
            ->findOne();

        $merchantOrderCountsTransfer = (new MerchantOrderCountsTransfer())
            ->fromArray($merchantOrderCounts, true);

        $totalsPerStore = $this->getOrderTotalsPerStore($idMerchant);
        foreach ($totalsPerStore as $totalPerStore) {
            $merchantOrderCountsTransfer->addTotalPerStore(
                $totalPerStore[OrderTransfer::STORE],
                $totalPerStore[MerchantOrderCountsTransfer::TOTAL_PER_STORE]
            );
        }

        return $merchantOrderCountsTransfer;
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
            ->addAsColumn(MerchantOrderTransfer::ID_MERCHANT_ORDER, SpyMerchantSalesOrderTableMap::COL_ID_MERCHANT_SALES_ORDER)
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
     * @module MerchantSalesOrder
     * @module MerchantOms
     * @module Sales
     * @module StateMachine
     *
     * @param \Generated\Shared\Transfer\MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery
     */
    protected function buildMerchantOrderItemTableBaseQuery(
        MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
    ): SpyMerchantSalesOrderItemQuery {
        $idMerchant = $merchantOrderItemTableCriteriaTransfer->requireIdMerchant()->getIdMerchant();

        $merchantSalesOrderItemQuery = $this->getFactory()->getMerchantSalesOrderItemPropelQuery();
        $merchantSalesOrderItemQuery
            ->joinSalesOrderItem()
            ->joinMerchantSalesOder()
            ->useSalesOrderItemQuery()
              ->joinMetadata()
            ->endUse()
            ->joinWithStateMachineItemState()
            ->addJoin(
                SpyMerchantSalesOrderTableMap::COL_MERCHANT_REFERENCE,
                SpyMerchantTableMap::COL_MERCHANT_REFERENCE,
                Criteria::INNER_JOIN
            )
            ->addAnd(SpyMerchantTableMap::COL_ID_MERCHANT, $idMerchant)
            ->filterByIdMerchantSalesOrderItem_In($merchantOrderItemTableCriteriaTransfer->getMerchantOrderItemIds())
            ->addAsColumn(MerchantOrderItemTransfer::ID_MERCHANT_ORDER_ITEM, SpyMerchantSalesOrderItemTableMap::COL_ID_MERCHANT_SALES_ORDER_ITEM)
            ->addAsColumn(MerchantOrderItemTransfer::FK_STATE_MACHINE_ITEM_STATE, SpyMerchantSalesOrderItemTableMap::COL_FK_STATE_MACHINE_ITEM_STATE)
            ->addAsColumn(MerchantOrderItemTransfer::STATE, SpyStateMachineItemStateTableMap::COL_NAME)
            ->addAsColumn(ItemTransfer::ID_SALES_ORDER_ITEM, SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM)
            ->addAsColumn(ItemTransfer::NAME, SpySalesOrderItemTableMap::COL_NAME)
            ->addAsColumn(ItemTransfer::SKU, SpySalesOrderItemTableMap::COL_SKU)
            ->addAsColumn(ItemTransfer::QUANTITY, SpySalesOrderItemTableMap::COL_QUANTITY)
            ->addAsColumn(ItemTransfer::CONCRETE_ATTRIBUTES, SpySalesOrderItemMetadataTableMap::COL_SUPER_ATTRIBUTES)
            ->addAsColumn(ProductImageTransfer::EXTERNAL_URL_SMALL, SpySalesOrderItemMetadataTableMap::COL_IMAGE)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);

        return $merchantSalesOrderItemQuery;
    }

    /**
     * @phpstan-param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<mixed> $merchantSalesOrderQuery
     *
     * @phpstan-return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<mixed>
     *
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

        return $merchantSalesOrderQuery->add($orderReferenceSearchCriteria);
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery $merchantSalesOrderItemQuery
     * @param \Generated\Shared\Transfer\MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery
     */
    protected function applyMerchantOrderItemSearch(
        SpyMerchantSalesOrderItemQuery $merchantSalesOrderItemQuery,
        MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
    ): SpyMerchantSalesOrderItemQuery {
        $searchTerm = $merchantOrderItemTableCriteriaTransfer->getSearchTerm();

        if (!$searchTerm) {
            return $merchantSalesOrderItemQuery;
        }

        $criteria = new Criteria();
        $orderItemNameSearchCriteria = $this->getOrderItemNameSearchCriteria($criteria, $searchTerm);
        $orderItemSkuSearchCriteria = $this->getOrderItemSkuSearchCriteria($criteria, $searchTerm);

        $orderItemNameSearchCriteria->addOr($orderItemSkuSearchCriteria);

        return $merchantSalesOrderItemQuery->add($orderItemNameSearchCriteria);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     * @param string $searchTerm
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion
     */
    protected function getOrderReferenceSearchCriteria(Criteria $criteria, string $searchTerm): LikeCriterion
    {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion $likeCriterion */
        $likeCriterion = $criteria->getNewCriterion(
            SpySalesOrderTableMap::COL_ORDER_REFERENCE,
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
    protected function getMerchantOrderReferenceSearchCriteria(Criteria $criteria, string $searchTerm): LikeCriterion
    {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion $likeCriterion */
        $likeCriterion = $criteria->getNewCriterion(
            SpyMerchantSalesOrderTableMap::COL_MERCHANT_SALES_ORDER_REFERENCE,
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
    protected function getOrderFirstNameSearchCriteria(Criteria $criteria, string $searchTerm): LikeCriterion
    {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion $likeCriterion */
        $likeCriterion = $criteria->getNewCriterion(
            SpySalesOrderTableMap::COL_FIRST_NAME,
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
    protected function getOrderLastNameSearchCriteria(Criteria $criteria, string $searchTerm): LikeCriterion
    {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion $likeCriterion */
        $likeCriterion = $criteria->getNewCriterion(
            SpySalesOrderTableMap::COL_LAST_NAME,
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
    protected function getOrderEmailSearchCriteria(Criteria $criteria, string $searchTerm): LikeCriterion
    {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion $likeCriterion */
        $likeCriterion = $criteria->getNewCriterion(
            SpySalesOrderTableMap::COL_EMAIL,
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
    protected function getOrderItemNameSearchCriteria(Criteria $criteria, string $searchTerm): LikeCriterion
    {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion $likeCriterion */
        $likeCriterion = $criteria->getNewCriterion(
            SpySalesOrderItemTableMap::COL_NAME,
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
    protected function getOrderItemSkuSearchCriteria(Criteria $criteria, string $searchTerm): LikeCriterion
    {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\LikeCriterion $likeCriterion */
        $likeCriterion = $criteria->getNewCriterion(
            SpySalesOrderItemTableMap::COL_SKU,
            '%' . $searchTerm . '%',
            Criteria::LIKE
        );

        return $likeCriterion->setIgnoreCase(true);
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
        $orderColumn = $merchantOrderTableCriteriaTransfer->getOrderBy() ?? static::COL_KEY_REFERENCE;
        $orderDirection = $merchantOrderTableCriteriaTransfer->getOrderDirection() ?? Criteria::DESC;

        $orderColumn = MerchantOrderTableDataMapper::MERCHANT_ORDER_DATA_COLUMN_MAP[$orderColumn] ?? $orderColumn;

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
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery $merchantSalesOrderItemQuery
     * @param \Generated\Shared\Transfer\MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery
     */
    protected function addMerchantOrderItemSorting(
        SpyMerchantSalesOrderItemQuery $merchantSalesOrderItemQuery,
        MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
    ): SpyMerchantSalesOrderItemQuery {
        $orderColumn = $merchantOrderItemTableCriteriaTransfer->getOrderBy() ?? static::COL_KEY_SKU;
        $orderDirection = $merchantOrderItemTableCriteriaTransfer->getOrderDirection() ?? Criteria::DESC;

        if (!$orderColumn || !$orderDirection) {
            return $merchantSalesOrderItemQuery;
        }

        $orderColumn = MerchantOrderItemTableDataMapper::MERCHANT_ORDER_ITEM_DATA_COLUMN_MAP[$orderColumn] ?? $orderColumn;

        if (in_array($orderColumn, [SpySalesOrderItemTableMap::COL_SKU], true)) {
            $merchantSalesOrderItemQuery = $this->addNaturalSorting($merchantSalesOrderItemQuery, $orderColumn, $orderDirection);
        }

        $merchantSalesOrderItemQuery->orderBy($orderColumn, $orderDirection);

        return $merchantSalesOrderItemQuery;
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
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery $merchantSalesOrderItemQuery
     * @param \Generated\Shared\Transfer\MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery
     */
    protected function addMerchantOrderItemFilters(
        SpyMerchantSalesOrderItemQuery $merchantSalesOrderItemQuery,
        MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
    ): SpyMerchantSalesOrderItemQuery {
        $merchantSalesOrderItemQuery = $this->addOrderItemStatesMerchantOrderItemFilter(
            $merchantSalesOrderItemQuery,
            $merchantOrderItemTableCriteriaTransfer
        );

        return $merchantSalesOrderItemQuery;
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
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery $merchantSalesOrderItemQuery
     * @param \Generated\Shared\Transfer\MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery
     */
    protected function addOrderItemStatesMerchantOrderItemFilter(
        SpyMerchantSalesOrderItemQuery $merchantSalesOrderItemQuery,
        MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
    ): SpyMerchantSalesOrderItemQuery {
        if (!$merchantOrderItemTableCriteriaTransfer->getFilterOrderItemStates()) {
            return $merchantSalesOrderItemQuery;
        }

        $merchantSalesOrderItemQuery->useStateMachineItemStateQuery()
                ->filterByName_In($merchantOrderItemTableCriteriaTransfer->getFilterOrderItemStates())
            ->endUse();

        return $merchantSalesOrderItemQuery;
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

    /**
     * @param int $idMerchant
     *
     * @return mixed[][]
     */
    protected function getOrderTotalsPerStore(int $idMerchant): array
    {
        $merchantSalesOrderQuery = $this->getFactory()->getMerchantSalesOrderPropelQuery();
        $merchantSalesOrderQuery = $this->filterMerchantSalesOrderQueryByIdMerchant($merchantSalesOrderQuery, $idMerchant);

        return $merchantSalesOrderQuery->joinOrder()
            ->addAsColumn(OrderTransfer::STORE, SpySalesOrderTableMap::COL_STORE)
            ->addAsColumn(MerchantOrderCountsTransfer::TOTAL_PER_STORE, 'COUNT(*)')
            ->useOrderQuery()
            ->groupByStore()
            ->endUse()
            ->select([
                OrderTransfer::STORE,
                MerchantOrderCountsTransfer::TOTAL_PER_STORE,
            ])
            ->find()
            ->toArray();
    }
}
