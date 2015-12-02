<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Sales\Code;

//@deprecated is not used

interface ExpensableTransferInterface
{

    /**
     * @param ExpenseItemInterface $expense
     *
     * @return self
     */
    public function removeExpense(ExpenseItemInterface $expense);

    /**
     * @param ExpenseItemInterface $expense
     *
     * @return self
     */
    public function addExpense(ExpenseItemInterface $expense);

    /**
     * @param ExpenseItemCollectionInterface $expenses
     *
     * @return self
     */
    public function setExpenses(ExpenseItemCollectionInterface $expenses);

    /**
     * @return ExpenseItemCollectionInterface|ExpenseItemInterface[]
     */
    public function getExpenses();

}
