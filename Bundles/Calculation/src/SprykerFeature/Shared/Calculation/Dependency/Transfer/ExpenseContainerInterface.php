<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use Generated\Shared\Transfer\ExpensesTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;

interface ExpenseContainerInterface
{
    /**
     * @return \ArrayObject|ExpenseItemInterface[]|ExpensesTransfer
     */
    public function getExpenses();

    /**
     * @param \ArrayObject $expenses
     *
     * @return $this
     */
    public function setExpenses(\ArrayObject $expenses);

    /**
     * @param ExpenseTransfer $expenseItem
     *
     * @return mixed
     */
    public function addExpense(ExpenseTransfer $expenseItem);

    /**
     * @param ExpenseItemInterface $expenseItem
     *
     * @return $this
     */

    // TODO check with Fabian Wesner if remove should be implemented
    // @see OrderTransfer
//    public function removeExpense(ExpenseItemInterface $expenseItem);
}
