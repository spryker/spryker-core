<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Customer;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;

class PaginatedCustomerOrderReader extends CustomerOrderReader
{
    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer, $idCustomer)
    {
        $ordersQuery = $this->queryContainer->queryCustomerOrders(
            $idCustomer,
            $orderListTransfer->getFilter()
        );

        $orderCollection = $this->getOrderCollection($orderListTransfer, $ordersQuery);

        $orders = $this->hydrateOrderListCollectionTransferFromEntityCollection($orderCollection);

        $orderListTransfer->setOrders($orders);

        return $this->updatePaginationTransfer($orderListTransfer, $ordersQuery);
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
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrder[]
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

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $ordersQuery
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function updatePaginationTransfer(OrderListTransfer $orderListTransfer, SpySalesOrderQuery $ordersQuery): OrderListTransfer
    {
        if ($orderListTransfer->getPagination()) {
            return $orderListTransfer;
        }

        return $orderListTransfer->setPagination((new PaginationTransfer())->setNbResults($ordersQuery->count()));
    }
}
