<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Refund\Persistence\SpyRefund;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Refund\Dependency\Facade\RefundToOmsInterface;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesSplitInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class Refund
{

    /**
     * @var \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesSplitInterface
     */
    protected $salesSplitFacade;

    /**
     * @var \Spryker\Zed\Refund\Dependency\Facade\RefundToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @param \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesSplitInterface $salesSplitFacade
     * @param \Spryker\Zed\Refund\Dependency\Facade\RefundToOmsInterface $omsFacade
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(
        RefundToSalesSplitInterface $salesSplitFacade,
        RefundToOmsInterface $omsFacade,
        SalesQueryContainerInterface $salesQueryContainer
    ) {
        $this->salesSplitFacade = $salesSplitFacade;
        $this->omsFacade = $omsFacade;
        $this->salesQueryContainer = $salesQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function saveRefund(RefundTransfer $refundTransfer)
    {
        $this->salesQueryContainer->getConnection()->beginTransaction();

        $refundEntity = new SpyRefund();
        $refundEntity->fromArray($refundTransfer->toArray());
        $refundEntity->save();

        $orderItems = $refundTransfer->getOrderItems();
        $processedOrderItems = $this->processItems($orderItems);

        $expenses = $refundTransfer->getExpenses();
        $processedExpenses = $this->processExpenses($expenses);

        if (!$processedOrderItems) {
            $this->salesQueryContainer->getConnection()->rollBack();

            return null;
        }

        $this->updateOrderItemsAfterRefund($processedOrderItems, $refundEntity->getIdRefund());
        $this->updateOrderExpensesAfterRefund($processedExpenses, $refundEntity->getIdRefund());

        $this->salesQueryContainer->getConnection()->commit();

        $orderItemsIds = [];
        /** @var \Generated\Shared\Transfer\ItemTransfer $processedOrderItem */
        foreach ($processedOrderItems as $processedOrderItem) {
            $orderItemsIds[] = $processedOrderItem->getIdSalesOrderItem();
        }

        $orderItems = $this->salesQueryContainer->querySalesOrderItem()
            ->filterByIdSalesOrderItem($orderItemsIds)
            ->find();
        $this->omsFacade->triggerEvent('start refund', $orderItems, []);

        $refundTransfer->setIdRefund($refundEntity->getIdRefund());

        return $refundTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return \ArrayObject
     */
    protected function processItems(\ArrayObject $orderItems)
    {
        $orderItemArray = new \ArrayObject();
        foreach ($orderItems as $orderItem) {
            if ($orderItem->getQuantity() < 1) {
                continue;
            }

            $itemSplitResponseTransfer = $this->salesSplitFacade->splitSalesOrderItem(
                $orderItem->getIdOrderItem(),
                $orderItem->getQuantity()
            );

            $idOrderItem = $orderItem->getIdOrderItem();
            if ($itemSplitResponseTransfer->getSuccess()) {
                $idOrderItem = $itemSplitResponseTransfer->getIdOrderItem();
            }

            $itemTransfer = new ItemTransfer();
            $itemTransfer->setIdSalesOrderItem($idOrderItem);
            $orderItemArray[] = $itemTransfer;
        }

        return $orderItemArray;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return \ArrayObject
     */
    protected function processExpenses(\ArrayObject $expenses)
    {
        $expensesArray = new \ArrayObject();
        foreach ($expenses as $expense) {
            if ($expense->getQuantity() < 1) {
                continue;
            }

            $expenseTransfer = new ExpenseTransfer();
            $expenseTransfer->setIdExpense($expense->getIdExpense());
            $expensesArray[] = $expenseTransfer;
        }

        return $expensesArray;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItems
     * @param int $idRefund
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function updateOrderItemsAfterRefund(\ArrayObject $orderItems, $idRefund)
    {
        foreach ($orderItems as $orderItem) {
            $orderItemEntity = $this->salesQueryContainer
                ->querySalesOrderItem()
                ->filterByIdSalesOrderItem($orderItem->getIdSalesOrderItem())
                ->findOne();

            $orderItemEntity->setFkRefund($idRefund);
            $orderItemEntity->save();
        }
    }


    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     * @param int $idRefund
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function updateOrderExpensesAfterRefund(\ArrayObject $expenses, $idRefund)
    {
        foreach ($expenses as $expense) {
            $expenseEntity = $this->salesQueryContainer
                ->querySalesExpense()
                ->filterByIdSalesExpense($expense->getIdExpense())
                ->findOne();

            $expenseEntity->setFkRefund($idRefund);
            $expenseEntity->save();
        }
    }

    /**
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    public function getRefundableItems($idOrder)
    {
        return $this->salesQueryContainer
            ->querySalesOrderItem()
            ->filterByFkSalesOrder($idOrder)
            ->filterByFkRefund(null, Criteria::ISNULL)
            ->find();
    }

    /**
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense[]
     */
    public function getRefundableExpenses($idOrder)
    {
        return $this->salesQueryContainer
            ->querySalesExpense()
            ->filterByFkSalesOrder($idOrder)
            ->filterByFkRefund(null, Criteria::ISNULL)
            ->find();
    }

}
