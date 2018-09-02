<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use Generated\Shared\Transfer\ExpenseTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

/**
 * @method \Spryker\Zed\Sales\Persistence\SalesPersistenceFactory getFactory()
 */
class SalesEntityManager extends AbstractEntityManager implements SalesEntityManagerInterface
{
    use TransactionTrait;

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function createExpense(ExpenseTransfer $expenseTransfer): ExpenseTransfer
    {
        $salesOrderExpenseEntity = $this->getFactory()
            ->createSalesExpenseMapper()
            ->mapExpenseTransferToSalesExpenseEntity($expenseTransfer);

        $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderExpenseEntity) {
            $salesOrderExpenseEntity->save();

            $this->addExpenseToOrder($salesOrderExpenseEntity);
        });

        $expenseTransfer->setIdSalesExpense($salesOrderExpenseEntity->getIdSalesExpense());

        return $expenseTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $spySalesExpense
     *
     * @return void
     */
    protected function addExpenseToOrder(SpySalesExpense $spySalesExpense): void
    {
        $salesOrder = $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByIdSalesOrder($spySalesExpense->getFkSalesOrder())
            ->findOne();

        $salesOrder->addExpense($spySalesExpense);
    }
}
