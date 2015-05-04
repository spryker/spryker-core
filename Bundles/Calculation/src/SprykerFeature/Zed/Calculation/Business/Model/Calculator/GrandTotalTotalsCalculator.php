<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemCollectionInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class GrandTotalTotalsCalculator extends AbstractCalculator implements
    TotalsCalculatorPluginInterface
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
     * @param LocatorLocatorInterface $locator
     * @param SubtotalTotalsCalculatorInterface $subtotalTotalsCalculatorInterface
     * @param ExpenseTotalsCalculatorInterface $expenseTotalsCalculator
     */
    public function __construct(
        LocatorLocatorInterface $locator,
        SubtotalTotalsCalculatorInterface $subtotalTotalsCalculatorInterface,
        ExpenseTotalsCalculatorInterface $expenseTotalsCalculator
    ) {
        parent::__construct($locator);

        $this->subtotalTotalsCalculator = $subtotalTotalsCalculatorInterface;
        $this->expenseTotalsCalculator = $expenseTotalsCalculator;
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param CalculableItemCollectionInterface $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        CalculableItemCollectionInterface $calculableItems
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
     * @param CalculableItemCollectionInterface $calculableItems
     * @return int
     */
    protected function calculateGrandTotal(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        CalculableItemCollectionInterface $calculableItems
    ) {
        $grandTotalWithoutDiscounts = $this->getSubtotal($totalsTransfer, $calculableItems);
        $grandTotalWithoutDiscounts += $this->getOrderExpenseTotal($totalsTransfer, $calculableContainer);

        return $grandTotalWithoutDiscounts;
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableItemCollectionInterface $calculableItems
     * @return int
     */
    protected function getSubtotal(
        TotalsInterface $totalsTransfer,
        CalculableItemCollectionInterface $calculableItems
    ) {
        if ($totalsTransfer->getSubtotal() !== 0) {
            return $totalsTransfer->getSubtotal();
        } else {
            return $this->subtotalTotalsCalculator->calculateSubtotal($calculableItems);
        }
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @return int
     */
    protected function getOrderExpenseTotal(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer
    ) {
        if ($totalsTransfer->getExpenses()->getTotalOrderAmount() !== 0) {
            return $totalsTransfer->getExpenses()->getTotalOrderAmount();
        } else {
            return $this->expenseTotalsCalculator->calculateExpenseTotal($calculableContainer);
        }
    }
}
