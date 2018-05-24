<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Customer;

use ArrayObject;
use Generated\Shared\Transfer\OrderListTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Spryker\Zed\Sales\Business\Model\Order\CustomerOrderOverviewHydratorInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

/**
 * @property \Spryker\Zed\Sales\Business\Model\Order\CustomerOrderOverviewHydratorInterface orderHydrator
 */
class PaginatedCustomerOrderOverview extends PaginatedCustomerOrderReader implements CustomerOrderOverviewInterface
{
    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Business\Model\Order\CustomerOrderOverviewHydratorInterface $orderHydrator
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        CustomerOrderOverviewHydratorInterface $orderHydrator,
        SalesToOmsInterface $omsFacade
    ) {
        parent::__construct($queryContainer, $orderHydrator, $omsFacade);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrdersOverview(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer->requireIdCustomer();

        $ordersQuery = $this->queryContainer->queryCustomerOrders(
            $orderListTransfer->getIdCustomer(),
            $orderListTransfer->getFilter()
        );

        $ordersQuery
            ->clearOrderByColumns()
            ->addDescendingOrderByColumn(SpySalesOrderTableMap::COL_CREATED_AT);
        $orderCollection = $this->getOrderCollection($orderListTransfer, $ordersQuery);

        return $this->prepareOrderListTransfer($orderListTransfer, $orderCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder[]|\Propel\Runtime\Collection\ObjectCollection $orderCollection
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function prepareOrderListTransfer(OrderListTransfer $orderListTransfer, $orderCollection): OrderListTransfer
    {
        $orders = new ArrayObject();
        foreach ($orderCollection as $salesOrderEntity) {
            if ($salesOrderEntity->countItems() === 0 || $this->excludeOrder($salesOrderEntity)) {
                continue;
            }

            $orderTransfer = $this->orderHydrator->hydrateOrderTransfer($salesOrderEntity);
            $orders->append($orderTransfer);
        }

        $orderListTransfer->setOrders($orders);

        return $orderListTransfer;
    }
}
