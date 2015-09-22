<?php

namespace SprykerFeature\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemsAndExpensesTransfer;
use Generated\Shared\Transfer\RefundExpenseTransfer;
use Generated\Shared\Transfer\RefundOrderItemTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Refund\Dependency\Facade\RefundToOmsInterface;
use SprykerFeature\Zed\Refund\Dependency\Facade\RefundToSalesInterface;
use SprykerFeature\Zed\Refund\Persistence\Propel\SpyRefund;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainer;

class Refund
{

    /**
     * @var RefundToSalesInterface
     */
    protected $salesFacade;

    /**
     * @var RefundToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var SalesQueryContainer
     */
    protected $queryContainer;

    /**
     * @param RefundToSalesInterface $salesFacade
     * @param RefundToOmsInterface $omsFacade
     * @param SalesQueryContainer $queryContainer
     */
    public function __construct(
        RefundToSalesInterface $salesFacade,
        RefundToOmsInterface $omsFacade,
        SalesQueryContainer $queryContainer
    ) {
        $this->salesFacade = $salesFacade;
        $this->omsFacade = $omsFacade;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param RefundTransfer $refundTransfer
     *
     * @return RefundTransfer
     */
    public function saveRefund(RefundTransfer $refundTransfer)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        $refundEntity = new SpyRefund();
        $refundEntity->fromArray($refundTransfer->toArray());
        $refundEntity->save();

        $orderItems = $refundTransfer->getOrderItems();
        $processedOrderItems = $this->processItems($orderItems);

        $expenses = $refundTransfer->getExpenses();
        $processedExpenses = $this->processExpenses($expenses);

        if (!$processedOrderItems) {
            $this->queryContainer->getConnection()->rollBack();
            return null;
        }

        $this->updateOrderItemsAndExpensesAfterRefund($refundEntity->getIdRefund(), $processedOrderItems, $processedExpenses);

        $this->queryContainer->getConnection()->commit();

        $orderItemsIds = [];
        /** @var ItemTransfer $processedOrderItem */
        foreach ($processedOrderItems as $processedOrderItem) {
            $orderItemsIds[] = $processedOrderItem->getIdSalesOrderItem();
        }

        $orderItems = $this->queryContainer->querySalesOrderItem()
            ->filterByIdSalesOrderItem($orderItemsIds)
            ->find();
        $this->omsFacade->triggerEvent('start refund', $orderItems, []);

        $refundTransfer->setIdRefund($refundEntity->getIdRefund());

        return $refundTransfer;
    }

    /**
     * @param \ArrayObject|RefundOrderItemTransfer[] $orderItems
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

            $itemSplitResponseTransfer = $this->salesFacade->splitSalesOrderItem($orderItem->getIdOrderItem(), $orderItem->getQuantity());
            if ($itemSplitResponseTransfer->getSuccess()) {
                $idOrderItem = $itemSplitResponseTransfer->getIdOrderItem();
            } else {
                $idOrderItem = $orderItem->getIdOrderItem();
            }

            $itemTransfer = new ItemTransfer();
            $itemTransfer->setIdSalesOrderItem($idOrderItem);
            $orderItemArray[] = $itemTransfer;
        }

        return $orderItemArray;
    }

    /**
     * @param \ArrayObject|RefundExpenseTransfer[] $expenses
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

    protected function updateOrderItemsAndExpensesAfterRefund($idRefund, $orderItemsArray, $expensesArray)
    {
        $orderItemsAndExpensesTransfer = new OrderItemsAndExpensesTransfer();

        $orderItemsAndExpensesTransfer->setOrderItems($orderItemsArray);
        $orderItemsAndExpensesTransfer->setExpenses($expensesArray);

        $this->salesFacade->updateOrderItemsAndExpensesAfterRefund($idRefund, $orderItemsAndExpensesTransfer);
    }

}
