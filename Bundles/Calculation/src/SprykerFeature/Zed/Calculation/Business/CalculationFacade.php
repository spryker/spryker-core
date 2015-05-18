<?php

namespace SprykerFeature\Zed\Calculation\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\CalculationConfig;

/**
 * @method CalculationDependencyContainer getDependencyContainer()
 * @method CalculationConfig getConfig()
 */
class CalculationFacade extends AbstractFacade
{

    /**
     * @param CalculableContainerInterface $calculableContainer
     * @return CalculableContainerInterface
     */
    public function recalculate(CalculableContainerInterface $calculableContainer)
    {

        $calculatorStack = $this->getDependencyContainer()->getConfig()->getCalculatorStack();

        return $this->getDependencyContainer()->getStackExecutor()->recalculate($calculatorStack, $calculableContainer);
    }

    /**
     * @param CalculableContainerInterface $calculableContainer
     * @return CalculableContainerInterface
     */
    public function performSoftRecalculation(CalculableContainerInterface $calculableContainer)
    {
        $calculatorStack = $this->getDependencyContainer()->getConfig()->getSoftCalculatorStack();

        return $this->getDependencyContainer()->getStackExecutor()->recalculate($calculatorStack, $calculableContainer);
    }

    /**
     * @param CalculableContainerInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     *
     * @return TotalsInterface
     */
    public function recalculateTotals(
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems = null
    ) {
        $calculatorStack = $this->getDependencyContainer()->getConfig()->getCalculatorStack();

        return $this->getDependencyContainer()->getStackExecutor()->recalculateTotals(
            $calculatorStack,
            $calculableContainer,
            $calculableItems
        );
    }

    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculateExpensePriceToPay(CalculableContainerInterface $calculableContainer)
    {
        $calculator = $this->getDependencyContainer()->getExpensePriceToPayCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateExpenseTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $calculator = $this->getDependencyContainer()->getExpenseTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateGrandTotalTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $calculator = $this->getDependencyContainer()->getGrandTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculateItemPriceToPay(CalculableContainerInterface $calculableContainer)
    {
        $calculator = $this->getDependencyContainer()->getItemPriceToPayCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculateOptionPriceToPay(CalculableContainerInterface $calculableContainer)
    {
        $calculator = $this->getDependencyContainer()->getOptionPriceToPayCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculateRemoveAllExpenses(CalculableContainerInterface $calculableContainer)
    {
        $calculator = $this->getDependencyContainer()->getRemoveAllExpensesCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculateRemoveTotals(CalculableContainerInterface $calculableContainer)
    {
        $calculator = $this->getDependencyContainer()->getRemoveTotalsCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateSubtotalTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $calculator = $this->getDependencyContainer()->getSubtotalTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateSubtotalWithoutItemExpensesTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $calculator = $this->getDependencyContainer()->getSubtotalWithoutItemExpensesTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateTaxTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $calculator = $this->getDependencyContainer()->getTaxTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }
}
