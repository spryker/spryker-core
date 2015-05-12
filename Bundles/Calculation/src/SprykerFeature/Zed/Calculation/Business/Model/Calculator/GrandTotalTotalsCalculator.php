<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class GrandTotalTotalsCalculator implements TotalsCalculatorPluginInterface
{
    /**
     * @var SubtotalTotalsCalculatorInterface
     */
    protected $subtotalTotalsCalculator;

    /**
     * @var ExpenseTotalsCalculatorInterface
     */
    protected $expenseTotalsCalculator;

    /**
     * @param SubtotalTotalsCalculatorInterface $subtotalTotalsCalculatorInterface
     * @param ExpenseTotalsCalculatorInterface $expenseTotalsCalculator
     */
    public function __construct(
        SubtotalTotalsCalculatorInterface $subtotalTotalsCalculatorInterface,
        ExpenseTotalsCalculatorInterface $expenseTotalsCalculator
    ) {
        $this->subtotalTotalsCalculator = $subtotalTotalsCalculatorInterface;
        $this->expenseTotalsCalculator = $expenseTotalsCalculator;
    }

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
        $grandTotalWithoutDiscounts = $this->calculateGrandTotal(
            $totalsTransfer,
            $calculableContainer,
            $calculableItems
        );
        $totalsTransfer->setGrandTotal($grandTotalWithoutDiscounts);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     *
     * @return int
     */
    protected function calculateGrandTotal(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $grandTotalWithoutDiscounts = $this->getSubtotal($totalsTransfer, $calculableItems);
        $grandTotalWithoutDiscounts += $this->getOrderExpenseTotal($totalsTransfer, $calculableContainer);

        return $grandTotalWithoutDiscounts;
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param \ArrayObject $calculableItems
     *
     * @return int
     */
    protected function getSubtotal(
        TotalsInterface $totalsTransfer,
        \ArrayObject $calculableItems
    ) {
        if (!is_null($totalsTransfer->getSubtotal())) {
            return $totalsTransfer->getSubtotal();
        } else {
            return $this->subtotalTotalsCalculator->calculateSubtotal($calculableItems);
        }
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     *
     * @return int
     */
    protected function getOrderExpenseTotal(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer
    ) {
        if (!is_null($totalsTransfer->getExpenses()->getTotalOrderAmount())) {
            return $totalsTransfer->getExpenses()->getTotalOrderAmount();
        } else {
            return $this->expenseTotalsCalculator->calculateExpenseTotal($calculableContainer);
        }
    }
}
