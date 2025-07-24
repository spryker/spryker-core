<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\OrderCollectionTransfer;
use Generated\Shared\Transfer\OrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTotalsTableMap;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Propel\PropelFilterCriteria;
use Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException;
use Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder;

/**
 * @method \Spryker\Zed\Sales\Persistence\SalesPersistenceFactory getFactory()
 */
class SalesRepository extends AbstractRepository implements SalesRepositoryInterface
{
    /**
     * @var string
     */
    protected const ID_SALES_ORDER = 'id_sales_order';

    /**
     * @var string
     */
    protected const COL_MAX_CREATED_AT = 'max_created_at';

    /**
     * @var string
     */
    protected const COL_FK_SALES_ORDER = 'fk_sales_order';

    /**
     * @var string
     */
    protected const ALIAS_LATEST_TOTALS = 'latest_totals';

    /**
     * @var array<string, string>
     */
    protected const SORT_KEYS_MAP = [
        'createdAt' => SpySalesOrderTableMap::COL_CREATED_AT,
        'updatedAt' => SpySalesOrderTableMap::COL_UPDATED_AT,
    ];

    /**
     * @param string $customerReference
     * @param string $orderReference
     *
     * @return int|null
     */
    public function findCustomerOrderIdByOrderReference(string $customerReference, string $orderReference): ?int
    {
        /** @var int|null $idSalesOrder */
        $idSalesOrder = $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByCustomerReference($customerReference)
            ->filterByOrderReference($orderReference)
            ->select([static::ID_SALES_ORDER])
            ->findOne();

        return $idSalesOrder;
    }

    /**
     * @param int $idOrderAddress
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    public function findOrderAddressByIdOrderAddress(int $idOrderAddress): ?AddressTransfer
    {
        $addressEntity = $this->getFactory()
            ->createSalesOrderAddressQuery()
            ->leftJoinWithCountry()
            ->filterByIdSalesOrderAddress($idOrderAddress)
            ->findOne();

        if ($addressEntity === null) {
            return null;
        }

        return $this->hydrateAddressTransferFromEntity($this->createOrderAddressTransfer(), $addressEntity);
    }

    /**
     * @module Oms
     *
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function getOrderItems(OrderItemFilterTransfer $orderItemFilterTransfer): array
    {
        $salesOrderItemQuery = $this->getFactory()
            ->createSalesOrderItemQuery()
            ->innerJoinWithOrder()
            ->leftJoinWithProcess()
            ->leftJoinWithState();

        $salesOrderItemQuery = $this->setOrderItemFilters($salesOrderItemQuery, $orderItemFilterTransfer);

        $salesOrderItemQuery = $this->buildQueryFromCriteria(
            $salesOrderItemQuery,
            $orderItemFilterTransfer->getFilter(),
        );

        $salesOrderItemQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);

        return $this->getFactory()
            ->createSalesOrderItemMapper()
            ->mapSalesOrderItemEntityCollectionToOrderItemTransfers($salesOrderItemQuery->find());
    }

    /**
     * @param array<int> $salesOrderIds
     *
     * @return array<string>
     */
    public function getCurrencyIsoCodesBySalesOrderIds(array $salesOrderIds): array
    {
        if (!$salesOrderIds) {
            return [];
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $currencyIsoCodes */
        $currencyIsoCodes = $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByIdSalesOrder_In($salesOrderIds)
            ->select([static::ID_SALES_ORDER, SpySalesOrderTableMap::COL_CURRENCY_ISO_CODE])
            ->find();

        return $currencyIsoCodes->toKeyValue(static::ID_SALES_ORDER, SpySalesOrderTableMap::COL_CURRENCY_ISO_CODE);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer
            ->requireFormat();

        $salesOrderQuery = $this->getFactory()
            ->createSalesOrderQuery()
            ->groupByIdSalesOrder()
            ->setIgnoreCase(true);

        $salesOrderQuery = $this->buildSearchOrdersQuery($salesOrderQuery, $orderListTransfer);

        if ($orderListTransfer->getPagination()) {
            $salesOrderQuery = $this->preparePagination($salesOrderQuery, $orderListTransfer->getPaginationOrFail());
        }

        $orderTransfers = $this->getFactory()
            ->createSalesOrderMapper()
            ->mapSalesOrderEntityCollectionToOrderTransfers($salesOrderQuery->find());

        return $orderListTransfer->setOrders(new ArrayObject($orderTransfers));
    }

    /**
     * @param array<int> $salesOrderIds
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function getSalesOrderItemsByOrderIds(array $salesOrderIds): array
    {
        $salesOrderItemQuery = $this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByFkSalesOrder_In($salesOrderIds);

        return $this->getFactory()
            ->createSalesOrderItemMapper()
            ->mapSalesOrderItemEntityCollectionToOrderItemTransfers($salesOrderItemQuery->find());
    }

    /**
     * @param array<int> $salesOrderIds
     *
     * @return array<\Generated\Shared\Transfer\TotalsTransfer>
     */
    public function getMappedSalesOrderTotalsBySalesOrderIds(array $salesOrderIds): array
    {
        $salesOrderTotalsSubQuerySql = $this->getLatestSalesOrderTotalsSubQuerySql($salesOrderIds);
        $salesOrderTotalsQuery = $this->getFactory()
            ->getSalesOrderTotalsPropelQuery()
            ->addAlias(static::ALIAS_LATEST_TOTALS, sprintf('(%s)', $salesOrderTotalsSubQuerySql))
            ->addJoin(
                [SpySalesOrderTotalsTableMap::COL_FK_SALES_ORDER, SpySalesOrderTotalsTableMap::COL_CREATED_AT],
                [sprintf('%s.%s', static::ALIAS_LATEST_TOTALS, static::COL_FK_SALES_ORDER), sprintf('%s.%s', static::ALIAS_LATEST_TOTALS, static::COL_MAX_CREATED_AT)],
                Criteria::INNER_JOIN,
            )
            ->filterByFkSalesOrder_In($salesOrderIds)
            ->groupByFkSalesOrder()
            ->orderByCreatedAt();

        return $this->getFactory()
            ->createSalesOrderMapper()
            ->mapSalesOrderTotalsEntityCollectionToMappedOrderTotalsByIdSalesOrder($salesOrderTotalsQuery->find());
    }

    /**
     * @param array<int> $salesOrderIds
     *
     * @return string
     */
    protected function getLatestSalesOrderTotalsSubQuerySql(array $salesOrderIds): string
    {
        $params = [];
        $salesOrderTotalsSubQuery = $this->getFactory()
            ->getSalesOrderTotalsPropelQuery()
            ->withColumn(sprintf('MAX(%s)', SpySalesOrderTotalsTableMap::COL_CREATED_AT), static::COL_MAX_CREATED_AT)
            ->select([static::COL_FK_SALES_ORDER, static::COL_MAX_CREATED_AT])
            ->filterByFkSalesOrder_In($salesOrderIds)
            ->groupByFkSalesOrder();

        return $salesOrderTotalsSubQuery->createSelectSql($params);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function buildSearchOrdersQuery(
        SpySalesOrderQuery $salesOrderQuery,
        OrderListTransfer $orderListTransfer
    ): SpySalesOrderQuery {
        $salesOrderQuery = $this->getFactory()
            ->createOrderSearchFilterFieldQueryBuilder()
            ->addSalesOrderQueryFilters($salesOrderQuery, $orderListTransfer);

        $queryJoinCollectionTransfer = $orderListTransfer->getQueryJoins();

        if ($queryJoinCollectionTransfer && $queryJoinCollectionTransfer->getQueryJoins()->count()) {
            $salesOrderQuery = $this->getFactory()
                ->createOrderSearchQueryJoinQueryBuilder()
                ->addSalesOrderQueryFilters($salesOrderQuery, $queryJoinCollectionTransfer);
        }

        if ($this->isSearchByAllFilterFieldSet($orderListTransfer->getFilterFields())) {
            $salesOrderQuery->where([OrderSearchFilterFieldQueryBuilder::CONDITION_GROUP_ALL]);
        }

        return $salesOrderQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function preparePagination(
        ModelCriteria $query,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        $page = $paginationTransfer
            ->requirePage()
            ->getPage();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPage();

        $propelModelPager = $query->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($propelModelPager->getNbResults())
            ->setFirstIndex($propelModelPager->getFirstIndex())
            ->setLastIndex($propelModelPager->getLastIndex())
            ->setFirstPage($propelModelPager->getFirstPage())
            ->setLastPage($propelModelPager->getLastPage())
            ->setNextPage($propelModelPager->getNextPage())
            ->setPreviousPage($propelModelPager->getPreviousPage());

        return $propelModelPager->getQuery();
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $addressEntity
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function hydrateAddressTransferFromEntity(
        AddressTransfer $addressTransfer,
        SpySalesOrderAddress $addressEntity
    ): AddressTransfer {
        $addressTransfer->fromArray($addressEntity->toArray(), true);
        $addressTransfer->setIso2Code($addressEntity->getCountry()->getIso2Code());

        return $addressTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createOrderAddressTransfer(): AddressTransfer
    {
        return new AddressTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListRequestTransfer $orderListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getCustomerOrderListByCustomerReference(OrderListRequestTransfer $orderListRequestTransfer): OrderListTransfer
    {
        $orderListQuery = $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByCustomerReference($orderListRequestTransfer->getCustomerReference());

        if ($orderListRequestTransfer->getOrderReferences()) {
            $orderListQuery->filterByOrderReference_In($orderListRequestTransfer->getOrderReferences());
        }

        $ordersCount = $orderListQuery->count();
        if (!$ordersCount) {
            return new OrderListTransfer();
        }

        $orderListQuery = $this->applyFilterToQuery($orderListQuery, $orderListRequestTransfer->getFilter());

        $orderListTransfer = $this->getFactory()
            ->createSalesOrderMapper()
            ->mapSalesOrderEntitiesToOrderListTransfer($orderListQuery->find()->getArrayCopy(), new OrderListTransfer());

        $orderListTransfer->setPagination((new PaginationTransfer())->setNbResults($ordersCount));

        return $orderListTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $orderListQuery
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filterTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function applyFilterToQuery(SpySalesOrderQuery $orderListQuery, ?FilterTransfer $filterTransfer): SpySalesOrderQuery
    {
        if ($filterTransfer) {
            if ($filterTransfer->getOrderBy() && isset(static::SORT_KEYS_MAP[$filterTransfer->getOrderBy()])) {
                $filterTransfer->setOrderBy(static::SORT_KEYS_MAP[$filterTransfer->getOrderBy()]);
            }

            $orderListQuery->mergeWith(
                (new PropelFilterCriteria($filterTransfer))->toCriteria(),
            );
        }

        return $orderListQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $salesOrderItemQuery
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function setOrderItemFilters(
        SpySalesOrderItemQuery $salesOrderItemQuery,
        OrderItemFilterTransfer $orderItemFilterTransfer
    ): SpySalesOrderItemQuery {
        if ($orderItemFilterTransfer->getSalesOrderItemIds()) {
            $salesOrderItemQuery->filterByIdSalesOrderItem_In(array_unique($orderItemFilterTransfer->getSalesOrderItemIds()));
        }

        if ($orderItemFilterTransfer->getSalesOrderItemUuids()) {
            $salesOrderItemQuery->filterByUuid_In(array_unique($orderItemFilterTransfer->getSalesOrderItemUuids()));
        }

        if ($orderItemFilterTransfer->getSalesOrderIds()) {
            $salesOrderItemQuery->filterByFkSalesOrder_In(array_unique($orderItemFilterTransfer->getSalesOrderIds()));
        }

        if ($orderItemFilterTransfer->getCustomerReferences()) {
            $salesOrderItemQuery
                ->useOrderQuery()
                    ->filterByCustomerReference_In(array_unique($orderItemFilterTransfer->getCustomerReferences()))
                ->endUse();
        }

        if ($orderItemFilterTransfer->getOrderReferences()) {
            $salesOrderItemQuery
                ->useOrderQuery()
                    ->filterByOrderReference_In(array_unique($orderItemFilterTransfer->getOrderReferences()))
                ->endUse();
        }

        if ($orderItemFilterTransfer->getItemStates()) {
            $salesOrderItemQuery
                ->useStateQuery()
                    ->filterByName_In(array_unique($orderItemFilterTransfer->getItemStates()))
                ->endUse();
        }

        return $salesOrderItemQuery;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\FilterFieldTransfer> $filterFieldTransfers
     *
     * @return bool
     */
    protected function isSearchByAllFilterFieldSet(ArrayObject $filterFieldTransfers): bool
    {
        foreach ($filterFieldTransfers as $filterFieldTransfer) {
            if ($filterFieldTransfer->getType() === OrderSearchFilterFieldQueryBuilder::SEARCH_TYPE_ALL) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderFilterTransfer $orderFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getSalesOrderDetails(OrderFilterTransfer $orderFilterTransfer): OrderTransfer
    {
        $orderEntity = $this->getSalesOrderEntity($orderFilterTransfer);

        return $this->createOrderTransfer($orderEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function getTotalCustomerOrderCount(OrderTransfer $orderTransfer): int
    {
        $customerReference = $orderTransfer->getCustomerReference();

        if ($customerReference === null) {
            return 0;
        }

        return $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByCustomerReference($customerReference)
            ->count();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return int
     */
    public function countUniqueProductsForOrder(int $idSalesOrder): int
    {
        return (int)$this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->withColumn('COUNT(*)', 'Count')
            ->select(['Count'])
            ->groupBySku()
            ->orderBy('Count')
            ->count();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCriteriaTransfer $orderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCollectionTransfer
     */
    public function getOrderCollection(OrderCriteriaTransfer $orderCriteriaTransfer): OrderCollectionTransfer
    {
        $orderCollectionTransfer = new OrderCollectionTransfer();

        $salesOrderQuery = $this->getFactory()
            ->createSalesOrderQuery()
            ->leftJoinWith('SpySalesOrder.BillingAddress billingAddress')
            ->leftJoinWith('billingAddress.Country billingCountry')
            ->leftJoinWith('SpySalesOrder.ShippingAddress shippingAddress')
            ->leftJoinWith('shippingAddress.Country shippingCountry');
        $salesOrderQuery = $this->applySalesOrderFilters($salesOrderQuery, $orderCriteriaTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $orderCriteriaTransfer->getSortCollection();
        $salesOrderQuery = $this->applySorting($salesOrderQuery, $sortTransfers);

        $paginationTransfer = $orderCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $salesOrderQuery = $this->applyPagination($salesOrderQuery, $paginationTransfer);
            $orderCollectionTransfer->setPagination($paginationTransfer);
        }

        $salesOrderEntitiesIndexedByIdSalesOrder = $salesOrderQuery->find()
            ->getArrayCopy('IdSalesOrder');
        $salesOrderEntitiesIndexedByIdSalesOrder = $this->expandSalesOrdersWithSalesOrderItems(
            $salesOrderEntitiesIndexedByIdSalesOrder,
        );
        $salesOrderEntitiesIndexedByIdSalesOrder = $this->expandSalesOrdersWithSalesExpenses(
            $salesOrderEntitiesIndexedByIdSalesOrder,
        );

        foreach ($salesOrderEntitiesIndexedByIdSalesOrder as $salesOrderEntity) {
            $orderCollectionTransfer->addOrder($this->createOrderTransfer($salesOrderEntity));
        }

        return $orderCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderFilterTransfer $orderFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderWithoutItems(OrderFilterTransfer $orderFilterTransfer): ?OrderTransfer
    {
        $orderFilterTransfer->requireSalesOrderId();

        $salesOrderEntity = $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByIdSalesOrder($orderFilterTransfer->getSalesOrderId())
            ->findOne();

        if ($salesOrderEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createSalesOrderMapper()
            ->mapSalesOrderEntityToSalesOrderTransfer($salesOrderEntity, new OrderTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer $salesExpenseCollectionDeleteCriteriaTransfer
     *
     * @return list<\Generated\Shared\Transfer\ExpenseTransfer>
     */
    public function getSalesExpensesBySalesExpenseCollectionDeleteCriteria(
        SalesExpenseCollectionDeleteCriteriaTransfer $salesExpenseCollectionDeleteCriteriaTransfer
    ): array {
        $salesExpenseQuery = $this->getFactory()->createSalesExpenseQuery();
        $salesExpenseQuery = $this->appySalesExpenseCollectionDeleteCriteriaFilters(
            $salesExpenseQuery,
            $salesExpenseCollectionDeleteCriteriaTransfer,
        );

        $salesExpenseEntities = $salesExpenseQuery->find();

        if ($salesExpenseEntities->count() === 0) {
            return [];
        }

        return $this->getFactory()
            ->createSalesExpenseMapper()
            ->mapSalesExpenseEntitiesToExpenseTransfers($salesExpenseEntities, []);
    }

    /**
     * @return list<string>
     */
    public function getOmsOrderItemStates(): array
    {
        return $this->getFactory()
            ->getOmsOrderItemStatePropelQuery()
            ->select([SpyOmsOrderItemStateTableMap::COL_NAME])
            ->orderBy(SpyOmsOrderItemStateTableMap::COL_ID_OMS_ORDER_ITEM_STATE)
            ->find()
            ->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderFilterTransfer $orderFilterTransfer
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function getSalesOrderEntity(OrderFilterTransfer $orderFilterTransfer): SpySalesOrder
    {
        $salesOrderQuery = $this->getFactory()->createSalesOrderQuery()
            ->setModelAlias('order')
            ->innerJoinWith('order.BillingAddress billingAddress')
            ->innerJoinWith('billingAddress.Country billingCountry')
            ->leftJoinWith('order.ShippingAddress shippingAddress')
            ->leftJoinWith('shippingAddress.Country shippingCountry');
        $salesOrderQuery = $this->setOrderFilters($salesOrderQuery, $orderFilterTransfer);
        $salesOrderQuery = $this->buildQueryFromCriteria(
            $salesOrderQuery,
            $orderFilterTransfer->getFilter(),
        );
        $salesOrderQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);
        $orderEntity = $salesOrderQuery->findOne();

        if ($orderEntity === null) {
            throw new InvalidSalesOrderException(
                sprintf(
                    'Order could not be found for ID %s',
                    $orderFilterTransfer->getSalesOrderId(),
                ),
            );
        }

        return $orderEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer(SpySalesOrder $salesOrderEntity): OrderTransfer
    {
        $orderTransfer = $this->getFactory()
            ->createSalesOrderMapper()
            ->mapSalesOrderEntityToSalesOrderTransfer($salesOrderEntity, new OrderTransfer());
        $orderTransfer = $this->setOrderTotals($salesOrderEntity, $orderTransfer);
        $orderTransfer = $this->setBillingAddress($salesOrderEntity, $orderTransfer);
        $orderTransfer = $this->setShippingAddress($salesOrderEntity, $orderTransfer);
        $orderTransfer = $this->setOrderExpenses($salesOrderEntity, $orderTransfer);
        $orderTransfer = $this->setMissingCustomer($salesOrderEntity, $orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\OrderFilterTransfer $orderFilterTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function setOrderFilters(SpySalesOrderQuery $salesOrderQuery, OrderFilterTransfer $orderFilterTransfer): SpySalesOrderQuery
    {
        if ($orderFilterTransfer->getSalesOrderId()) {
            $salesOrderQuery->filterByIdSalesOrder($orderFilterTransfer->getSalesOrderId());
        }
        if ($orderFilterTransfer->getCustomerReference()) {
            $salesOrderQuery->filterByCustomerReference($orderFilterTransfer->getCustomerReference());
        }
        if ($orderFilterTransfer->getOrderReference()) {
            $salesOrderQuery->filterByOrderReference($orderFilterTransfer->getOrderReference());
        }

        return $salesOrderQuery;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function setOrderTotals(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer): OrderTransfer
    {
        $salesOrderTotalsEntity = $orderEntity->getLastOrderTotals();

        if (!$salesOrderTotalsEntity) {
            return $orderTransfer;
        }

        $totalsTransfer = $this->getFactory()
            ->createSalesOrderTotalsMapper()
            ->mapSalesOrderTotalsTransfer($salesOrderTotalsEntity, new TotalsTransfer(), new TaxTotalTransfer());

        $orderTransfer->setTotals($totalsTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function setBillingAddress(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer): OrderTransfer
    {
        $billingAddressTransfer = $this->getFactory()
            ->createSalesOrderAddressMapper()
            ->mapAddressEntityToAddressTransfer(new AddressTransfer(), $orderEntity->getBillingAddress());

        $orderTransfer->setBillingAddress($billingAddressTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function setShippingAddress(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer): OrderTransfer
    {
        $orderShippingAddressEntity = $orderEntity->getShippingAddress();

        if ($orderShippingAddressEntity === null) {
            return $orderTransfer;
        }

        $shippingAddressTransfer = $this->getFactory()
            ->createSalesOrderAddressMapper()
            ->mapAddressEntityToAddressTransfer(new AddressTransfer(), $orderShippingAddressEntity);

        $orderTransfer->setShippingAddress($shippingAddressTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function setOrderExpenses(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($orderEntity->getExpenses(new Criteria()) as $expenseEntity) {
            $expenseTransfer = $this->getFactory()
                ->createSalesExpenseMapper()
                ->mapExpenseEntityToSalesExpenseTransfer(new ExpenseTransfer(), $expenseEntity);

            $orderTransfer->addExpense($expenseTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function setMissingCustomer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer): OrderTransfer
    {
        if (!$orderEntity->getCustomer()) {
            $orderTransfer->setCustomerReference(null);
            $orderTransfer->setFkCustomer(null);
        }

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\OrderCriteriaTransfer $orderCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function applySalesOrderFilters(
        SpySalesOrderQuery $salesOrderQuery,
        OrderCriteriaTransfer $orderCriteriaTransfer
    ): SpySalesOrderQuery {
        $orderConditionsTransfer = $orderCriteriaTransfer->getOrderConditions();
        if ($orderConditionsTransfer === null) {
            return $salesOrderQuery;
        }

        if ($orderConditionsTransfer->getSalesOrderIds() !== []) {
            $salesOrderQuery->filterByIdSalesOrder_In($orderConditionsTransfer->getSalesOrderIds());
        }

        if ($orderConditionsTransfer->getOrderReferences() !== []) {
            $salesOrderQuery->filterByOrderReference_In($orderConditionsTransfer->getOrderReferences());
        }

        if ($orderConditionsTransfer->getCustomerReferences() !== []) {
            $salesOrderQuery->filterByCustomerReference_In($orderConditionsTransfer->getCustomerReferences());
        }

        return $salesOrderQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applySorting(
        ModelCriteria $modelCriteria,
        ArrayObject $sortTransfers
    ): ModelCriteria {
        foreach ($sortTransfers as $sortTransfer) {
            $modelCriteria->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $modelCriteria;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyPagination(
        ModelCriteria $modelCriteria,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($modelCriteria->count());

            return $modelCriteria
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage()) {
            $propelModelPager = $modelCriteria->paginate(
                $paginationTransfer->getPageOrFail(),
                $paginationTransfer->getMaxPerPageOrFail(),
            );

            $paginationTransfer->setNbResults($propelModelPager->getNbResults())
                ->setFirstIndex($propelModelPager->getFirstIndex())
                ->setLastIndex($propelModelPager->getLastIndex())
                ->setFirstPage($propelModelPager->getFirstPage())
                ->setLastPage($propelModelPager->getLastPage())
                ->setNextPage($propelModelPager->getNextPage())
                ->setPreviousPage($propelModelPager->getPreviousPage());

            return $propelModelPager->getQuery();
        }

        return $modelCriteria;
    }

    /**
     * @param array<int, \Orm\Zed\Sales\Persistence\SpySalesOrder> $salesOrderEntitiesIndexedByIdSalesOrder
     *
     * @return array<int, \Orm\Zed\Sales\Persistence\SpySalesOrder>
     */
    protected function expandSalesOrdersWithSalesOrderItems(array $salesOrderEntitiesIndexedByIdSalesOrder): array
    {
        $salesOrderItemEntities = $this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByFkSalesOrder_In(array_keys($salesOrderEntitiesIndexedByIdSalesOrder))
            ->find();

        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $idSalesOrder = $salesOrderItemEntity->getFkSalesOrder();
            if (!isset($salesOrderEntitiesIndexedByIdSalesOrder[$idSalesOrder])) {
                continue;
            }

            $salesOrderEntitiesIndexedByIdSalesOrder[$idSalesOrder]->addItem($salesOrderItemEntity);
        }

        return $salesOrderEntitiesIndexedByIdSalesOrder;
    }

    /**
     * @param array<int, \Orm\Zed\Sales\Persistence\SpySalesOrder> $salesOrderEntitiesIndexedByIdSalesOrder
     *
     * @return array<int, \Orm\Zed\Sales\Persistence\SpySalesOrder>
     */
    protected function expandSalesOrdersWithSalesExpenses(array $salesOrderEntitiesIndexedByIdSalesOrder): array
    {
        $salesExpenseEntities = $this->getFactory()
            ->createSalesExpenseQuery()
            ->filterByFkSalesOrder_In(array_keys($salesOrderEntitiesIndexedByIdSalesOrder))
            ->find();

        foreach ($salesExpenseEntities as $salesExpenseEntity) {
            $idSalesOrder = $salesExpenseEntity->getFkSalesOrder();
            if (!isset($salesOrderEntitiesIndexedByIdSalesOrder[$idSalesOrder])) {
                continue;
            }

            $salesOrderEntitiesIndexedByIdSalesOrder[$idSalesOrder]->addExpense($salesExpenseEntity);
        }

        return $salesOrderEntitiesIndexedByIdSalesOrder;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery $salesExpenseQuery
     * @param \Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer $salesExpenseCollectionDeleteCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    protected function appySalesExpenseCollectionDeleteCriteriaFilters(
        SpySalesExpenseQuery $salesExpenseQuery,
        SalesExpenseCollectionDeleteCriteriaTransfer $salesExpenseCollectionDeleteCriteriaTransfer
    ): SpySalesExpenseQuery {
        if ($salesExpenseCollectionDeleteCriteriaTransfer->getSalesOrderIds()) {
            $salesExpenseQuery->filterByFkSalesOrder_In($salesExpenseCollectionDeleteCriteriaTransfer->getSalesOrderIds());
        }

        if ($salesExpenseCollectionDeleteCriteriaTransfer->getTypes()) {
            $salesExpenseQuery->filterByType_In($salesExpenseCollectionDeleteCriteriaTransfer->getTypes());
        }

        return $salesExpenseQuery;
    }
}
