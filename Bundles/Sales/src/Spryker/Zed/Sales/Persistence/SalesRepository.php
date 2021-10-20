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
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
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
     * @var array
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
            $orderItemFilterTransfer->getFilter()
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

        return $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByIdSalesOrder_In($salesOrderIds)
            ->select([static::ID_SALES_ORDER, SpySalesOrderTableMap::COL_CURRENCY_ISO_CODE])
            ->find()
            ->toKeyValue(static::ID_SALES_ORDER, SpySalesOrderTableMap::COL_CURRENCY_ISO_CODE);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer
            ->requireFormat()
            ->requirePagination();

        $salesOrderQuery = $this->getFactory()
            ->createSalesOrderQuery()
            ->groupByIdSalesOrder()
            ->setIgnoreCase(true);

        $salesOrderQuery = $this->buildSearchOrdersQuery($salesOrderQuery, $orderListTransfer);
        $salesOrderQuery = $this->preparePagination($salesOrderQuery, $orderListTransfer->getPagination());

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
        $salesOrderTotalsQuery = $this->getFactory()
            ->getSalesOrderTotalsPropelQuery()
            ->filterByFkSalesOrder_In($salesOrderIds)
            ->groupByFkSalesOrder()
            ->orderByCreatedAt();

        return $this->getFactory()
            ->createSalesOrderMapper()
            ->mapSalesOrderTotalsEntityCollectionToMappedOrderTotalsByIdSalesOrder($salesOrderTotalsQuery->find());
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
                $filterTransfer->setOrderBy(
                    isset(static::SORT_KEYS_MAP[$filterTransfer->getOrderBy()]) ? static::SORT_KEYS_MAP[$filterTransfer->getOrderBy()] : null
                );
            }

            $orderListQuery->mergeWith(
                (new PropelFilterCriteria($filterTransfer))->toCriteria()
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
        $orderEntity = $this->getSalesOrderEnity($orderFilterTransfer);

        $orderTransfer = $this->getFactory()->createSalesOrderMapper()->mapSalesOrderEntityToSalesOrderTransfer($orderEntity, new OrderTransfer());
        $orderTransfer = $this->setOrderTotals($orderEntity, $orderTransfer);
        $orderTransfer = $this->setBillingAddress($orderEntity, $orderTransfer);
        $orderTransfer = $this->setShippingAddress($orderEntity, $orderTransfer);
        $orderTransfer = $this->setOrderExpenses($orderEntity, $orderTransfer);
        $orderTransfer = $this->setMissingCustomer($orderEntity, $orderTransfer);

        return $orderTransfer;
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
     * @param \Generated\Shared\Transfer\OrderFilterTransfer $orderFilterTransfer
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function getSalesOrderEnity(OrderFilterTransfer $orderFilterTransfer): SpySalesOrder
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
            $orderFilterTransfer->getFilter()
        );
        $salesOrderQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);
        $orderEntity = $salesOrderQuery->findOne();

        if ($orderEntity === null) {
            throw new InvalidSalesOrderException(
                sprintf(
                    'Order could not be found for ID %s',
                    $orderFilterTransfer->getSalesOrderId()
                )
            );
        }

        return $orderEntity;
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
}
