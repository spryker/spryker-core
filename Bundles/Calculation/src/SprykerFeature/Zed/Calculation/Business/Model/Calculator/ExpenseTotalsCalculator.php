<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculationExpenseTotalItemTransfer;
use Generated\Shared\Transfer\CalculationExpenseTotalsTransfer;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseItemInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class ExpenseTotalsCalculator implements
    TotalsCalculatorPluginInterface,
    ExpenseTotalsCalculatorInterface
{
    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $expenseTotal = $this->createExpenseTotalTransfer($calculableContainer, $calculableItems);
        $totalsTransfer->setExpenses($expenseTotal);
    }

    /**
     * @param CalculableContainerInterface $calculableContainer
     *
     * @return int
     */
    public function calculateExpenseTotal(CalculableContainerInterface $calculableContainer)
    {
        $orderExpensesTotal = 0;
        foreach ($calculableContainer->getExpenses() as $expense) {
            $orderExpensesTotal += $expense->getGrossPrice();
        }

        return $orderExpensesTotal;
    }

    /**
     * @param \ArrayObject|CalculableItemInterface[] $calculableItems
     * @return int
     */
    protected function calculateItemExpenseTotal(\ArrayObject $calculableItems)
    {
        $itemExpenseTotal = 0;
        foreach ($calculableItems as $item) {
            foreach ($item->getExpenses() as $expense) {
                $itemExpenseTotal += $expense->getGrossPrice();
            }
        }

        return $itemExpenseTotal;
    }

    /**
     * @param CalculableContainerInterface $calculableContainer
     * @param \ArrayObject|CalculableItemInterface[] $calculableItems
     *
     * @return array
     */
    protected function sumExpenseItems(
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $orderExpenseItems = [];
        foreach ($calculableContainer->getExpenses() as $expense) {
            $this->transformExpenseToExpenseTotalItemInArray($expense, $orderExpenseItems);
        }

        foreach ($calculableItems as $item) {
            foreach ($item->getExpenses() as $expense) {
                $this->transformExpenseToExpenseTotalItemInArray($expense, $orderExpenseItems);
            }
        }

        return $orderExpenseItems;
    }

    /**
     * @param ExpenseItemInterface $expense
     * @param $arrayOfExpenseTotalItems
     */
    protected function transformExpenseToExpenseTotalItemInArray($expense, &$arrayOfExpenseTotalItems)
    {
        if (!isset($arrayOfExpenseTotalItems[$expense->getType()])) {
            $item = new CalculationExpenseTotalItemTransfer();
            $item->setName($expense->getName());
            $item->setType($expense->getType());
        } else {
            $item = $arrayOfExpenseTotalItems[$expense->getType()];
        }

        $item->setGrossPrice($item->getGrossPrice() + $expense->getGrossPrice());
        $item->setPriceToPay($item->getPriceToPay() + $expense->getPriceToPay());
        $arrayOfExpenseTotalItems[$expense->getType()] = $item;
    }

    /**
     * @param CalculableContainerInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     *
     * @return CalculationExpenseTotalsTransfer
     */
    protected function createExpenseTotalTransfer(
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $expenseTotalTransfer = new CalculationExpenseTotalsTransfer();
        $expenseTotalTransfer->setTotalOrderAmount($this->calculateExpenseTotal($calculableContainer));
        $expenseTotalTransfer->setTotalItemAmount($this->calculateItemExpenseTotal($calculableItems));

        foreach ($this->sumExpenseItems($calculableContainer, $calculableItems) as $expenseTotalItem) {
            $expenseTotalTransfer->addExpenseItem($expenseTotalItem);
        }

        return $expenseTotalTransfer;
    }
}
