<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Customer;

use ArrayObject;
use Generated\Shared\Transfer\OrderListTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Sales\Business\Model\Order\CustomerOrderOverviewHydratorInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class PaginatedCustomerOrderOverview implements CustomerOrderOverviewInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\Order\CustomerOrderOverviewHydratorInterface
     */
    protected $customerOrderOverviewHydrator;

    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface[]
     */
    protected $searchOrderExpanderPlugins;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Business\Model\Order\CustomerOrderOverviewHydratorInterface $customerOrderOverviewHydrator
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface[] $searchOrderExpanderPlugins
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        CustomerOrderOverviewHydratorInterface $customerOrderOverviewHydrator,
        SalesToOmsInterface $omsFacade,
        array $searchOrderExpanderPlugins
    ) {
        $this->queryContainer = $queryContainer;
        $this->omsFacade = $omsFacade;
        $this->customerOrderOverviewHydrator = $customerOrderOverviewHydrator;
        $this->searchOrderExpanderPlugins = $searchOrderExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrdersOverview(OrderListTransfer $orderListTransfer, $idCustomer): OrderListTransfer
    {
        $ordersQuery = $this->queryContainer->queryCustomerOrders(
            $idCustomer,
            $orderListTransfer->getFilter()
        );

        if (!$ordersQuery->getOrderByColumns()) {
            $ordersQuery->addDescendingOrderByColumn(SpySalesOrderTableMap::COL_CREATED_AT);
        }

        $orderCollection = $this->getOrderCollection($orderListTransfer, $ordersQuery);

        $orders = new ArrayObject();
        foreach ($orderCollection as $salesOrderEntity) {
            if ($salesOrderEntity->countItems() === 0) {
                continue;
            }

            if ($this->excludeOrder($salesOrderEntity)) {
                continue;
            }

            $orderTransfer = $this->customerOrderOverviewHydrator->hydrateOrderTransfer($salesOrderEntity);
            $orders->append($orderTransfer);
        }

        $orderTransfers = $this->executeSearchOrderExpanderPlugins($orders->getArrayCopy());
        $orderListTransfer->setOrders(new ArrayObject($orderTransfers));

        return $orderListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    protected function executeSearchOrderExpanderPlugins(array $orderTransfers): array
    {
        foreach ($this->searchOrderExpanderPlugins as $searchOrderExpanderPlugin) {
            $orderTransfers = $searchOrderExpanderPlugin->expand($orderTransfers);
        }

        return $orderTransfers;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return bool
     */
    protected function excludeOrder(SpySalesOrder $salesOrderEntity): bool
    {
        $excludeFromCustomer = $this->omsFacade->isOrderFlaggedExcludeFromCustomer(
            $salesOrderEntity->getIdSalesOrder()
        );

        return $excludeFromCustomer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $ordersQuery
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getOrderCollection(OrderListTransfer $orderListTransfer, SpySalesOrderQuery $ordersQuery)
    {
        if ($orderListTransfer->getPagination() !== null) {
            return $this->paginateOrderCollection($orderListTransfer, $ordersQuery);
        }

        return $ordersQuery->find();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $ordersQuery
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function paginateOrderCollection(OrderListTransfer $orderListTransfer, SpySalesOrderQuery $ordersQuery)
    {
        $paginationTransfer = $orderListTransfer->getPagination();

        $page = $paginationTransfer
            ->requirePage()
            ->getPage();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPage();

        $paginationModel = $ordersQuery->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults());
        $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
        $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
        $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $paginationTransfer->setNextPage($paginationModel->getNextPage());
        $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

        $orderListTransfer->setPagination($paginationTransfer);

        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrder[] $collection */
        $collection = $paginationModel->getResults();

        return $collection;
    }
}
