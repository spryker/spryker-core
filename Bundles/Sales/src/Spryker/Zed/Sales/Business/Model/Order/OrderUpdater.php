<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderUpdater implements OrderUpdaterInterface
{

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     */
    public function __construct(SalesQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function update(OrderTransfer $orderTransfer, $idSalesOrder)
    {
        $orderEntity = $this->queryContainer
            ->querySalesOrderById($idSalesOrder)
            ->findOne();

        if (empty($orderEntity)) {
            return false;
        }

        $this->hydrateEntityFromOrderTransfer($orderTransfer, $orderEntity);
        $orderEntity->save();

        $this->saveOrderTotals($orderTransfer, $orderEntity);
        $this->updateOrderItems($orderTransfer, $orderEntity);
        $this->updateOrderExpenses($orderTransfer, $orderEntity);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function hydrateEntityFromOrderTransfer(OrderTransfer $orderTransfer, SpySalesOrder $orderEntity)
    {
        $orderEntity->fromArray($orderTransfer->modifiedToArray());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function updateOrderItems(OrderTransfer $orderTransfer, SpySalesOrder $orderEntity)
    {
        foreach ($orderEntity->getItems() as $salesOrderItemEntity) {
            foreach ($orderTransfer->getItems() as $itemTransfer) {
                if ($salesOrderItemEntity->getIdSalesOrderItem() !== $itemTransfer->getIdSalesOrderItem()) {
                    continue;
                }

                $salesOrderItemEntity->fromArray($itemTransfer->modifiedToArray());
                $salesOrderItemEntity->save();
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function saveOrderTotals(OrderTransfer $orderTransfer, SpySalesOrder $orderEntity)
    {
         $taxTotal = 0;
        if ($orderTransfer->getTotals()->getTaxTotal()) {
            $taxTotal = $orderTransfer->getTotals()->getTaxTotal()->getAmount();
        }

         $salesOrderTotalsEntity = new SpySalesOrderTotals();
         $salesOrderTotalsEntity->setFkSalesOrder($orderEntity->getIdSalesOrder());
         $salesOrderTotalsEntity->fromArray($orderTransfer->getTotals()->toArray());
         $salesOrderTotalsEntity->setTaxTotal($taxTotal);
         $salesOrderTotalsEntity->setCanceledTotal($orderTransfer->getTotals()->getCanceledTotal());
         $salesOrderTotalsEntity->setOrderExpenseTotal($orderTransfer->getTotals()->getExpenseTotal());
         $salesOrderTotalsEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function updateOrderExpenses(OrderTransfer $orderTransfer, SpySalesOrder $orderEntity)
    {
        foreach ($orderEntity->getExpenses() as $expenseEntity) {
            foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
                if ($expenseTransfer->getIdSalesExpense() !== $expenseEntity->getIdSalesExpense()) {
                    continue;
                }

                $expenseEntity->fromArray($expenseTransfer->modifiedToArray());
                $expenseEntity->save();
            }
        }
    }

}
