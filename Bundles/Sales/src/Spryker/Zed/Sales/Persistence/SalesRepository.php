<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Formatter\ObjectFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Sales\Persistence\SalesPersistenceFactory getFactory()
 */
class SalesRepository extends AbstractRepository implements SalesRepositoryInterface
{
    protected const ID_SALES_ORDER = 'id_sales_order';

    /**
     * @param string $customerReference
     * @param string $orderReference
     *
     * @return int|null
     */
    public function findCustomerOrderIdByOrderReference(string $customerReference, string $orderReference): ?int
    {
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
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer->requireFormat();

        $salesOrderMapper = $this->getFactory()->createSalesOrderMapper();

        $salesOrderQuery = $this->getFactory()->createSalesOrderQuery();
        $salesOrderQuery = $this->setSalesOrderSearchFilters($salesOrderQuery, $orderListTransfer);

        $salesOrderEntityCollection = $this->buildQueryFromCriteria($salesOrderQuery, $orderListTransfer->getFilter())
            ->setFormatter(ObjectFormatter::class)
            ->find();

        $orderTransfers = $salesOrderMapper->mapSalesOrderEntityCollectionToOrderTransfers(
            $salesOrderEntityCollection,
            new ArrayObject()
        );

        $orderIds = $this->getOrderIdsFromOrderTransfers($orderTransfers);

        $salesOrderTotalsEntityCollection = $this->getOrderTotalsByOrderIds($orderIds);

        $orderTransfers = $salesOrderMapper->mapSalesOrderTotalsEntityCollectionToOrderTransfers(
            $salesOrderTotalsEntityCollection,
            $orderTransfers
        );

        if ($orderListTransfer->getFormat()->getExpandWithItems()) {
            $salesOrderItemEntityCollection = $this->getOrderItemsByOrderIds($orderIds);

            $orderTransfers = $salesOrderMapper->mapSalesOrderItemEntityCollectionToOrderTransfers(
                $salesOrderItemEntityCollection,
                $orderTransfers
            );
        }

        return $orderListTransfer->setOrders($orderTransfers);
    }

    /**
     * @param int[] $orderIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    protected function getOrderItemsByOrderIds(array $orderIds): ObjectCollection
    {
        $salesOrderItemQuery = $this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByFkSalesOrder_In($orderIds);

        return $salesOrderItemQuery->find();
    }

    /**
     * @param int[] $orderIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderTotals[]
     */
    protected function getOrderTotalsByOrderIds(array $orderIds): ObjectCollection
    {
        $salesOrderTotalsQuery = $this->getFactory()
            ->getSalesOrderTotalsPropelQuery()
            ->filterByFkSalesOrder_In($orderIds)
            ->orderByCreatedAt(Criteria::DESC);

        return $salesOrderTotalsQuery->find();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function setSalesOrderSearchFilters(
        SpySalesOrderQuery $salesOrderQuery,
        OrderListTransfer $orderListTransfer
    ): SpySalesOrderQuery {
        $orderListTransfer->requireFormat();
        $orderListTransfer->requireCustomer();

        $customerReference = $orderListTransfer->getCustomer()->getCustomerReference();

        if ($customerReference) {
            $salesOrderQuery->filterByCustomerReference($customerReference);
        }

        return $salesOrderQuery;
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
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return int[]
     */
    protected function getOrderIdsFromOrderTransfers(ArrayObject $orderTransfers): array
    {
        $orderIds = [];

        foreach ($orderTransfers as $orderTransfer) {
            $orderIds[] = $orderTransfer->getIdSalesOrder();
        }

        return $orderIds;
    }
}
