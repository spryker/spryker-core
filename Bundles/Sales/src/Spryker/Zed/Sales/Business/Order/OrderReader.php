<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\OrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderReader implements OrderReaderInterface
{
    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface $orderHydrator
     */
    public function __construct(
        protected SalesQueryContainerInterface $queryContainer,
        protected OrderHydratorInterface $orderHydrator
    ) {
    }

    /**
     * @param int $idSalesOrder
     *
     * @return array<string>
     */
    public function getDistinctOrderStates(int $idSalesOrder): array
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
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrderItem(int $idSalesOrderItem): ?OrderTransfer
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

    /**
     * This method allows to fetch orders by given Criteria
     * The Criteria currently uses either the orderReference or the salesOrderId (BC)
     *
     * @param \Generated\Shared\Transfer\OrderCriteriaTransfer $orderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByOrderCriteria(OrderCriteriaTransfer $orderCriteriaTransfer): ?OrderTransfer
    {
        $salesOrderQuery = $this->queryContainer
            ->querySalesOrderDetailsWithoutShippingAddress(null)
            ->leftJoinWithLocale();

        $salesOrderQuery = $this->applyOrderCriteria($salesOrderQuery, $orderCriteriaTransfer);

        $orderEntity = $salesOrderQuery->findOne();

        if ($orderEntity === null) {
            return null;
        }

        $orderTransfer = $this->orderHydrator
            ->applyOrderTransferHydrators($orderEntity);

        return $this->expandWithLocale($orderTransfer, $orderEntity);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $salesOrderQuery
     * @param \Generated\Shared\Transfer\OrderCriteriaTransfer $orderCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function applyOrderCriteria(SpySalesOrderQuery $salesOrderQuery, OrderCriteriaTransfer $orderCriteriaTransfer): SpySalesOrderQuery
    {
        $orderConditions = $orderCriteriaTransfer->getOrderConditions();

        $idSalesOrder = current($orderConditions->getSalesOrderIds());

        if ($idSalesOrder) {
            $salesOrderQuery->filterByIdSalesOrder($idSalesOrder);
        }

        $orderReference = current($orderConditions->getOrderReferences());

        if ($orderReference) {
            $salesOrderQuery->filterByOrderReference($orderReference);
        }

        return $salesOrderQuery;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrder(int $idSalesOrder): ?OrderTransfer
    {
        $orderEntity = $this->queryContainer
            ->querySalesOrderDetailsWithoutShippingAddress($idSalesOrder)
            ->leftJoinWithLocale()
            ->findOne();

        if ($orderEntity === null) {
            return null;
        }

        $orderTransfer = $this->orderHydrator
            ->applyOrderTransferHydrators($orderEntity);

        $orderTransfer = $this->expandWithLocale($orderTransfer, $orderEntity);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandWithLocale(OrderTransfer $orderTransfer, SpySalesOrder $orderEntity): OrderTransfer
    {
        if (!$orderEntity->getLocale()) {
            return $orderTransfer;
        }
        $localeTransfer = (new LocaleTransfer())
            ->fromArray($orderEntity->getLocale()->toArray(), true);

        return $orderTransfer->setLocale($localeTransfer);
    }
}
