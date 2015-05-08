<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use Generated\Shared\Transfer\CalculationExpenseTransfer;

interface ExpenseContainerInterface
{
    /**
     * @return \ArrayObject|ExpenseItemInterface[]
     */
    public function getExpenses();

    /**
     * @param \ArrayObject $expenses
     *
     * @return $this
     */
    public function setExpenses(\ArrayObject $expenses);

    /**
     * @param CalculationExpenseTransfer $expenseItem
     *
     * @return mixed
     */
    public function addExpense(CalculationExpenseTransfer $expenseItem);

    /**
     * @param ExpenseItemInterface $expenseItem
     *
     * @return $this
     */

    // TODO check with Fabian Wesner if remove should be implemented
    // @see SalesOrderTransfer
//    public function removeExpense(ExpenseItemInterface $expenseItem);
}
