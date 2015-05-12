<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

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
     * @param ExpenseItemInterface $expenseItem
     *
     * @return $this
     */
    public function addExpense(ExpenseItemInterface $expenseItem);

    /**
     * @param ExpenseItemInterface $expenseItem
     *
     * @return $this
     */
    public function removeExpense(ExpenseItemInterface $expenseItem);
}
