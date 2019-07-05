<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Expense;

use Generated\Shared\Transfer\ExpenseTransfer;
use Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface;

class ExpenseWriter implements ExpenseWriterInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface
     */
    protected $salesEntityManager;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface $salesEntityManager
     */
    public function __construct(SalesEntityManagerInterface $salesEntityManager)
    {
        $this->salesEntityManager = $salesEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function createSalesExpense(ExpenseTransfer $expenseTransfer): ExpenseTransfer
    {
        return $this->salesEntityManager->createSalesExpense($expenseTransfer);
    }
}
