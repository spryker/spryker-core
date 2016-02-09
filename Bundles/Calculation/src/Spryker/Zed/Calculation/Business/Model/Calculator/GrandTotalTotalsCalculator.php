<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class GrandTotalTotalsCalculator implements TotalsCalculatorPluginInterface
{

    /**
     * @var \Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculatorInterface
     */
    protected $subtotalTotalsCalculator;

    /**
     * @var \Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculatorInterface
     */
    protected $expenseTotalsCalculator;

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculatorInterface $subtotalTotalsCalculatorInterface
     * @param \Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculatorInterface $expenseTotalsCalculator
     */
    public function __construct(
        SubtotalTotalsCalculatorInterface $subtotalTotalsCalculatorInterface,
        ExpenseTotalsCalculatorInterface $expenseTotalsCalculator
    ) {
        $this->subtotalTotalsCalculator = $subtotalTotalsCalculatorInterface;
        $this->expenseTotalsCalculator = $expenseTotalsCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateTotals(
        TotalsTransfer $totalsTransfer,
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
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return int
     */
    protected function calculateGrandTotal(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $grandTotalWithoutDiscounts = $this->getSubtotal($totalsTransfer, $calculableItems);
        $grandTotalWithoutDiscounts += $this->getOrderExpenseTotal($totalsTransfer, $calculableContainer);

        return $grandTotalWithoutDiscounts;
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return int
     */
    protected function getSubtotal(TotalsTransfer $totalsTransfer, $calculableItems)
    {
        if ($totalsTransfer->getSubtotal()) {
            return $totalsTransfer->getSubtotal();
        } else {
            return $this->subtotalTotalsCalculator->calculateSubtotal($calculableItems);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return int
     */
    protected function getOrderExpenseTotal(TotalsTransfer $totalsTransfer, CalculableInterface $calculableContainer)
    {
        if ($totalsTransfer->getExpenses()->getTotalOrderAmount() !== null) {
            return $totalsTransfer->getExpenses()->getTotalOrderAmount();
        } else {
            return $this->expenseTotalsCalculator->calculateExpenseTotal($calculableContainer);
        }
    }

}
