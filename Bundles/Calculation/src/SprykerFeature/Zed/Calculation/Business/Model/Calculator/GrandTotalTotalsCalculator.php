<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\TotalsInterface;
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
     * @param OrderInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        OrderInterface $calculableContainer,
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
     * @param OrderInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     *
     * @return int
     */
    protected function calculateGrandTotal(
        TotalsInterface $totalsTransfer,
        OrderInterface $calculableContainer,
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
    protected function getSubtotal(TotalsInterface $totalsTransfer, \ArrayObject $calculableItems) {
        if ($totalsTransfer->getSubtotal()) {
            return $totalsTransfer->getSubtotal();
        } else {
            return $this->subtotalTotalsCalculator->calculateSubtotal($calculableItems);
        }
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param OrderInterface $calculableContainer
     *
     * @return int
     */
    protected function getOrderExpenseTotal(TotalsInterface $totalsTransfer, OrderInterface $calculableContainer)
    {
        if (!is_null($totalsTransfer->getExpenses()->getTotalOrderAmount())) {
            return $totalsTransfer->getExpenses()->getTotalOrderAmount();
        } else {
            return $this->expenseTotalsCalculator->calculateExpenseTotal($calculableContainer);
        }
    }
}
