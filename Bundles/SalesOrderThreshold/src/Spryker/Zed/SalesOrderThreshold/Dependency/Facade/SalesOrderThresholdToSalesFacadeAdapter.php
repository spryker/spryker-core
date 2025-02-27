<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Dependency\Facade;

use Exception;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesExpenseCollectionResponseTransfer;

class SalesOrderThresholdToSalesFacadeAdapter implements SalesOrderThresholdToSalesFacadeInterface
{
    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     */
    public function __construct($salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function createSalesExpense(ExpenseTransfer $expenseTransfer): ExpenseTransfer
    {
        return $this->salesFacade->createSalesExpense($expenseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer $salesExpenseCollectionDeleteCriteriaTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\SalesExpenseCollectionResponseTransfer
     */
    public function deleteSalesExpenseCollection(
        SalesExpenseCollectionDeleteCriteriaTransfer $salesExpenseCollectionDeleteCriteriaTransfer
    ): SalesExpenseCollectionResponseTransfer {
        if (!method_exists($this->salesFacade, 'deleteSalesExpenseCollection')) {
            throw new Exception('Method `SalesFacade::deleteSalesExpenseCollection()` was implemented in SalesFacade^11.55.0. Please update your Sales module.');
        }

        return $this->salesFacade->deleteSalesExpenseCollection($salesExpenseCollectionDeleteCriteriaTransfer);
    }
}
