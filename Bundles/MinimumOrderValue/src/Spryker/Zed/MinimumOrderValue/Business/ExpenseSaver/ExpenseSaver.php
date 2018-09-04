<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\ExpenseSaver;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToSalesFacadeInterface;

class ExpenseSaver implements ExpenseSaverInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToSalesFacadeInterface $salesFacade
     */
    public function __construct(MinimumOrderValueToSalesFacadeInterface $salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function saveSalesOrderMinimumOrderValueExpense(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SaveOrderTransfer {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== MinimumOrderValueConfig::THRESHOLD_EXPENSE_TYPE) {
                continue;
            }

            $saveOrderTransfer = $this->addExpenseToOrder($expenseTransfer, $saveOrderTransfer);
        }

        return $saveOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function addExpenseToOrder(
        ExpenseTransfer $expenseTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SaveOrderTransfer {
        $expenseTransfer->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $expenseTransfer = $this->salesFacade->createSalesExpense($expenseTransfer);
        $saveOrderTransfer->addOrderExpense($expenseTransfer);

        return $saveOrderTransfer;
    }
}
