<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

/**
 * @deprecated Use Spryker\Zed\Sales\Business\Order\OrderReader instead.
 */
class OrderReader implements OrderReaderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface
     */
    protected $orderHydrator;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface $orderHydrator
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        OrderHydratorInterface $orderHydrator
    ) {
        $this->queryContainer = $queryContainer;
        $this->orderHydrator = $orderHydrator;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string[]
     */
    public function getDistinctOrderStates($idSalesOrder)
    {
        $orderItems = $this->queryContainer
            ->querySalesOrderItemsByIdSalesOrder($idSalesOrder)
            ->find();

        $states = [];
        foreach ($orderItems as $orderItem) {
            $states[$orderItem->getState()->getName()] = $orderItem->getState()->getName();
        }

        return $states;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrder(int $idSalesOrder): ?OrderTransfer
    {
        $orderEntity = $this->queryContainer
            ->querySalesOrderDetails($idSalesOrder)
            ->findOne();

        if (!$orderEntity) {
            return null;
        }

        return $this->orderHydrator->hydrateOrderTransferFromPersistenceBySalesOrder($orderEntity);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrderItem($idSalesOrderItem)
    {
        $orderItem = $this->queryContainer
            ->querySalesOrderItem()
            ->filterByIdSalesOrderItem($idSalesOrderItem)
            ->findOne();

        if (!$orderItem) {
            return null;
        }

        return $this->orderHydrator->hydrateBaseOrderTransfer($orderItem->getOrder());
    }
}
