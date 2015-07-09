<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
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
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
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
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     *
     * @return int
     */
    protected function calculateGrandTotal(
        TotalsInterface $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $grandTotalWithoutDiscounts = $this->getSubtotal($totalsTransfer, $calculableItems);
        $grandTotalWithoutDiscounts += $this->getOrderExpenseTotal($totalsTransfer, $calculableContainer);

        return $grandTotalWithoutDiscounts;
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param $calculableItems
     *
     * @return int
     */
    protected function getSubtotal(TotalsInterface $totalsTransfer, $calculableItems) {
        if ($totalsTransfer->getSubtotal()) {
            return $totalsTransfer->getSubtotal();
        } else {
            return $this->subtotalTotalsCalculator->calculateSubtotal($calculableItems);
        }
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableInterface $calculableContainer
     *
     * @return int
     */
    protected function getOrderExpenseTotal(TotalsInterface $totalsTransfer, CalculableInterface $calculableContainer)
    {
        if (!is_null($totalsTransfer->getExpenses()->getTotalOrderAmount())) {
            return $totalsTransfer->getExpenses()->getTotalOrderAmount();
        } else {
            return $this->expenseTotalsCalculator->calculateExpenseTotal($calculableContainer);
        }
    }

}
