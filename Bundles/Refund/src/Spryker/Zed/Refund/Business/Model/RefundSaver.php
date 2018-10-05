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
use Spryker\Zed\Refund\Dependency\Facade\RefundToCalculationInterface;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class RefundSaver implements RefundSaverInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @var \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\Refund\Dependency\Facade\RefundToCalculationInterface
     */
    protected $calculationFacade;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainer
     * @param \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface $saleFacade
     * @param \Spryker\Zed\Refund\Dependency\Facade\RefundToCalculationInterface $calculationFacade
     */
    public function __construct(
        SalesQueryContainerInterface $salesQueryContainer,
        RefundToSalesInterface $saleFacade,
        RefundToCalculationInterface $calculationFacade
    ) {
        $this->salesQueryContainer = $salesQueryContainer;
        $this->salesFacade = $saleFacade;
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return bool
     */
    public function saveRefund(RefundTransfer $refundTransfer)
    {
        $this->salesQueryContainer->getConnection()->beginTransaction();

        $this->updateOrderItems($refundTransfer);
        $this->updateExpenses($refundTransfer);
        $this->storeRefund($refundTransfer);
        $this->recalculateOrder($refundTransfer);

        return $this->salesQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    protected function storeRefund(RefundTransfer $refundTransfer)
    {
        $refundEntity = $this->buildRefundEntity($refundTransfer);

        $this->saveRefundEntity($refundEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return \Orm\Zed\Refund\Persistence\SpyRefund
     */
    protected function buildRefundEntity(RefundTransfer $refundTransfer)
    {
        $refundEntity = new SpyRefund();
        $refundEntity->fromArray($refundTransfer->toArray());

        return $refundEntity;
    }

    /**
     * @param \Orm\Zed\Refund\Persistence\SpyRefund $refundEntity
     *
     * @return void
     */
    protected function saveRefundEntity(SpyRefund $refundEntity)
    {
        $refundEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    protected function updateOrderItems(RefundTransfer $refundTransfer)
    {
        foreach ($refundTransfer->getItems() as $itemTransfer) {
            $salesOrderItemEntity = $this->getSalesOrderItemEntity($itemTransfer);
            $salesOrderItemEntity->setCanceledAmount($itemTransfer->getCanceledAmount());
            $salesOrderItemEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function getSalesOrderItemEntity(ItemTransfer $itemTransfer)
    {
        $salesOrderItemEntity = $this->salesQueryContainer
            ->querySalesOrderItem()
            ->findOneByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem());

        return $salesOrderItemEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    protected function updateExpenses(RefundTransfer $refundTransfer)
    {
        foreach ($refundTransfer->getExpenses() as $expenseTransfer) {
            $salesExpenseEntity = $this->getExpenseEntity($expenseTransfer);
            $salesExpenseEntity->setCanceledAmount($expenseTransfer->getCanceledAmount());
            $salesExpenseEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    protected function getExpenseEntity(ExpenseTransfer $expenseTransfer)
    {
        $salesExpenseEntity = $this->salesQueryContainer
            ->querySalesExpense()
            ->findOneByIdSalesExpense($expenseTransfer->getIdSalesExpense());

        return $salesExpenseEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    protected function recalculateOrder(RefundTransfer $refundTransfer)
    {
        $orderTransfer = $this->salesFacade->getOrderByIdSalesOrder($refundTransfer->getFkSalesOrder());
        $orderTransfer = $this->calculationFacade->recalculateOrder($orderTransfer);
        $this->salesFacade->updateOrder($orderTransfer, $refundTransfer->getFkSalesOrder());
    }
}
