<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

interface ExpenseTotalsInterface
{
    /**
     * @param int $totalOrderAmount
     *
     * @return $this
     */
    public function setTotalOrderAmount($totalOrderAmount);

    /**
     * @return int
     */
    public function getTotalOrderAmount();

    /**
     * @param int $totalItemAmount
     *
     * @return $this
     */
    public function setTotalItemAmount($totalItemAmount);

    /**
     * @return int
     */
    public function getTotalItemAmount();

    /**
     * @param int $totalAmount
     *
     * @return $this
     */
    public function setTotalAmount($totalAmount);

    /**
     * @return int
     */
    public function getTotalAmount();

    /**
     * @param \ArrayObject $expenseItems
     *
     * @return $this
     */
    public function setExpenseItems(\ArrayObject $expenseItems);

    /**
     * @return ExpenseTotalItemInterface[]|\ArrayObject
     */
    public function getExpenseItems();

    /**
     * @param ExpenseTotalItemInterface|ExpenseTotalItemInterface $expenseItem
     *
     * @return $this
     */
    public function addExpenseItem(ExpenseTotalItemInterface $expenseItem);

    /**
     * @param ExpenseTotalItemInterface $expenseItem
     *
     * @return $this
     */
    public function removeExpenseItem(ExpenseTotalItemInterface $expenseItem);
}
