<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Replacer;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig;
use Spryker\Zed\SalesOrderThreshold\Business\ExpenseSaver\ExpenseSaverInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToSalesFacadeInterface;

class SalesOrderThresholdExpensesReplacer implements SalesOrderThresholdExpensesReplacerInterface
{
    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Business\ExpenseSaver\ExpenseSaverInterface $expenseSaver
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToSalesFacadeInterface $salesFacade
     */
    public function __construct(protected ExpenseSaverInterface $expenseSaver, protected SalesOrderThresholdToSalesFacadeInterface $salesFacade)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function replaceSalesOrderThresholdExpenses(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $salesExpenseCollectionDeleteCriteriaTransfer = (new SalesExpenseCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrder($saveOrderTransfer->getIdSalesOrderOrFail())
            ->addType(SalesOrderThresholdConfig::THRESHOLD_EXPENSE_TYPE);
        $this->salesFacade->deleteSalesExpenseCollection($salesExpenseCollectionDeleteCriteriaTransfer);

        $this->expenseSaver->saveSalesOrderSalesOrderThresholdExpense($quoteTransfer, $saveOrderTransfer);
    }
}
