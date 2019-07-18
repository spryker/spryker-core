<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use Generated\Shared\Transfer\ExpenseTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Sales\Persistence\SalesPersistenceFactory getFactory()
 */
class SalesEntityManager extends AbstractEntityManager implements SalesEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function createSalesExpense(ExpenseTransfer $expenseTransfer): ExpenseTransfer
    {
        $salesOrderExpenseEntity = $this->getFactory()
            ->createSalesExpenseMapper()
            ->mapExpenseTransferToSalesExpenseEntity($expenseTransfer);

        $salesOrderExpenseEntity->save();

        $expenseTransfer->setIdSalesExpense($salesOrderExpenseEntity->getIdSalesExpense());

        return $expenseTransfer;
    }
}
