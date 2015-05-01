<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

interface ExpenseContainerInterface
{
    /**
     * @return ExpenseItemCollectionInterface|ExpenseItemInterface[]
     */
    public function getExpenses();

    /**
     * @param ExpenseItemCollectionInterface $expenses
     *
     * @return $this
     */
    public function setExpenses(ExpenseItemCollectionInterface $expenses);

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
