<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\OrderSaver;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MinimumOrderValue\Dependency\QueryContainer\MinimumOrderValueToSalesQueryContainerInterface;
use Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig;

class OrderMinimumOrderValueFeesSaver implements OrderMinimumOrderValueFeesSaverInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\QueryContainer\MinimumOrderValueToSalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @var \Pyz\Zed\MinimumOrderValue\MinimumOrderValueConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\QueryContainer\MinimumOrderValueToSalesQueryContainerInterface $salesQueryContainer
     * @param \Pyz\Zed\MinimumOrderValue\MinimumOrderValueConfig $config
     */
    public function __construct(
        MinimumOrderValueToSalesQueryContainerInterface $salesQueryContainer,
        MinimumOrderValueConfig $config
    ) {
        $this->salesQueryContainer = $salesQueryContainer;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderMinimumOrderValueFees(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer, $saveOrderTransfer) {
            $this->saveOrderMinimumOrderValueFeesTransaction($quoteTransfer, $saveOrderTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function saveOrderMinimumOrderValueFeesTransaction(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $salesOrderEntity = $this->getSalesOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== $this->config->getMinimumOrderValueExpenseType()) {
                continue;
            }

            $this->addExpenseToOrder($expenseTransfer, $salesOrderEntity, $saveOrderTransfer);
        }
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function getSalesOrderByIdSalesOrder($idSalesOrder)
    {
        return $this->salesQueryContainer->querySalesOrderById($idSalesOrder)->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function addExpenseToOrder(
        ExpenseTransfer $expenseTransfer,
        SpySalesOrder $salesOrderEntity,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $salesOrderExpenseEntity = $this->createOrderExpenseEntity($expenseTransfer);
        $salesOrderExpenseEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesOrderExpenseEntity->save();

        $this->setCheckoutResponseExpenses($saveOrderTransfer, $expenseTransfer, $salesOrderExpenseEntity);

        $salesOrderEntity->addExpense($salesOrderExpenseEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    protected function createOrderExpenseEntity(
        ExpenseTransfer $expenseTransfer
    ): SpySalesExpense {
        $salesOrderExpenseEntity = new SpySalesExpense();
        $salesOrderExpenseEntity->fromArray($expenseTransfer->toArray());
        $salesOrderExpenseEntity->setGrossPrice($expenseTransfer->getSumGrossPrice());
        $salesOrderExpenseEntity->setNetPrice($expenseTransfer->getSumNetPrice());
        $salesOrderExpenseEntity->setPrice($expenseTransfer->getSumPrice());
        $salesOrderExpenseEntity->setTaxAmount($expenseTransfer->getSumTaxAmount());
        $salesOrderExpenseEntity->setDiscountAmountAggregation($expenseTransfer->getSumDiscountAmountAggregation());
        $salesOrderExpenseEntity->setPriceToPayAggregation($expenseTransfer->getSumPriceToPayAggregation());

        return $salesOrderExpenseEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $salesOrderExpenseEntity
     *
     * @return void
     */
    protected function setCheckoutResponseExpenses(
        SaveOrderTransfer $saveOrderTransfer,
        ExpenseTransfer $expenseTransfer,
        SpySalesExpense $salesOrderExpenseEntity
    ): void {
        $orderExpense = clone $expenseTransfer;
        $orderExpense->setIdSalesExpense($salesOrderExpenseEntity->getIdSalesExpense());
        $saveOrderTransfer->addOrderExpense($orderExpense);
    }
}
