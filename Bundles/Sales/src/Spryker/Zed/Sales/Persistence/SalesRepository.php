<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
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
        $orderListTransfer->requirePagination();

        $salesOrderQuery = $this->getFactory()
            ->createSalesOrderQuery()
            ->groupByIdSalesOrder();

        $salesOrderQuery = $this->setSalesOrderQuerySearchFilters(
            $salesOrderQuery,
            $orderListTransfer
        );

        $salesOrderQuery = $this->buildQueryFromCriteria(
            $salesOrderQuery,
            $orderListTransfer->getFilter()
        )->setFormatter(ObjectFormatter::class);

        $salesOrderQuery = $this->preparePagination(
            $salesOrderQuery,
            $orderListTransfer->getPagination()
        );

        return $this->mapSalesOrderEntityCollectionToOrderListTransfer(
            $salesOrderQuery->find(),
            $orderListTransfer
        );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function setSalesOrderQuerySearchFilters(
        SpySalesOrderQuery $salesOrderQuery,
        OrderListTransfer $orderListTransfer
    ): SpySalesOrderQuery {
        $orderListTransfer->requireCustomer();

        $customerReference = $orderListTransfer->getCustomer()->getCustomerReference();

        if ($customerReference) {
            $salesOrderQuery->filterByCustomerReference($customerReference);
        }

        $salesOrderQuery = $this->getFactory()
            ->createOrderSearchFilterFieldQueryBuilder()
            ->addSalesOrderQueryFilters($salesOrderQuery, $orderListTransfer);

        $queryJoinCollectionTransfer = $orderListTransfer->getQueryJoins();

        if ($queryJoinCollectionTransfer && $queryJoinCollectionTransfer->getQueryJoins()->count()) {
            $salesOrderQuery = $this->getFactory()
                ->createOrderSearchQueryJoinQueryBuilder()
                ->addSalesOrderQueryFilters($salesOrderQuery, $queryJoinCollectionTransfer);
        }

        return $salesOrderQuery;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $salesOrderEntityCollection
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function mapSalesOrderEntityCollectionToOrderListTransfer(
        ObjectCollection $salesOrderEntityCollection,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        $salesOrderMapper = $this->getFactory()->createSalesOrderMapper();

        $orderTransfers = $salesOrderMapper->mapSalesOrderEntityCollectionToOrderTransfers(
            $salesOrderEntityCollection,
            new ArrayObject()
        );

        $salesOrderIds = $this->getSalesOrderIdsFromOrderTransfers($orderTransfers);

        $salesOrderTotalsEntityCollection = $this->getSalesOrderTotalsBySalesOrderIds($salesOrderIds);

        $orderTransfers = $salesOrderMapper->mapSalesOrderTotalsEntityCollectionToOrderTransfers(
            $salesOrderTotalsEntityCollection,
            $orderTransfers
        );

        if ($orderListTransfer->getFormat()->getExpandWithItems()) {
            $salesOrderItemEntityCollection = $this->getSalesOrderItemsByOrderIds($salesOrderIds);

            $orderTransfers = $salesOrderMapper->mapSalesOrderItemEntityCollectionToOrderTransfers(
                $salesOrderItemEntityCollection,
                $orderTransfers
            );
        }

        return $orderListTransfer->setOrders($orderTransfers);
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
     * @param int[] $salesOrderIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    protected function getSalesOrderItemsByOrderIds(array $salesOrderIds): ObjectCollection
    {
        $salesOrderItemQuery = $this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByFkSalesOrder_In($salesOrderIds);

        return $salesOrderItemQuery->find();
    }

    /**
     * @param int[] $salesOrderIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderTotals[]
     */
    protected function getSalesOrderTotalsBySalesOrderIds(array $salesOrderIds): ObjectCollection
    {
        $salesOrderTotalsQuery = $this->getFactory()
            ->getSalesOrderTotalsPropelQuery()
            ->filterByFkSalesOrder_In($salesOrderIds)
            ->orderByCreatedAt(Criteria::DESC);

        return $salesOrderTotalsQuery->find();
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
    protected function getSalesOrderIdsFromOrderTransfers(ArrayObject $orderTransfers): array
    {
        $salesOrderIds = [];

        foreach ($orderTransfers as $orderTransfer) {
            $salesOrderIds[] = $orderTransfer->getIdSalesOrder();
        }

        return $salesOrderIds;
    }
}
