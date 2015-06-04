<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\CalculationConfig;
use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\TotalsInterface;

/**
 * @method CalculationDependencyContainer getDependencyContainer()
 * @method CalculationConfig getConfig()
 */
class CalculationFacade extends AbstractFacade
{

    /**
     * @param OrderInterface $calculableContainer
     * @return OrderInterface
     */
    public function recalculate(OrderInterface $calculableContainer)
    {

        $calculatorStack = $this->getDependencyContainer()->getConfig()->getCalculatorStack();

        return $this->getDependencyContainer()->getStackExecutor()->recalculate($calculatorStack, $calculableContainer);
    }

    /**
     * @param OrderInterface $calculableContainer
     * @return OrderInterface
     */
    public function recalculate2(CalculableInterface $calculableContainer)
    {
        $calculatorStack = $this->getDependencyContainer()->getConfig()->getCalculatorStack();

        return $this->getDependencyContainer()->getStackExecutor()->recalculate($calculatorStack, $calculableContainer);
    }

    /**
     * @param OrderInterface $calculableContainer
     * @return OrderInterface
     */
    public function performSoftRecalculation(OrderInterface $calculableContainer)
    {
        $calculatorStack = $this->getDependencyContainer()->getConfig()->getSoftCalculatorStack();

        return $this->getDependencyContainer()->getStackExecutor()->recalculate($calculatorStack, $calculableContainer);
    }

    /**
     * @param OrderInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     *
     * @return TotalsInterface
     */
    public function recalculateTotals(
        OrderInterface $calculableContainer,
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
     * @param OrderInterface $calculableContainer
     */
    public function recalculateExpensePriceToPay(OrderInterface $calculableContainer)
    {
        $calculator = $this->getDependencyContainer()->getExpensePriceToPayCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param OrderInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateExpenseTotals(
        TotalsInterface $totalsTransfer,
        OrderInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $calculator = $this->getDependencyContainer()->getExpenseTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param OrderInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateGrandTotalTotals(
        TotalsInterface $totalsTransfer,
        OrderInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $calculator = $this->getDependencyContainer()->getGrandTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @param OrderInterface $calculableContainer
     */
    public function recalculateItemPriceToPay(OrderInterface $calculableContainer)
    {
        $calculator = $this->getDependencyContainer()->getItemPriceToPayCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param OrderInterface $calculableContainer
     */
    public function recalculateOptionPriceToPay(OrderInterface $calculableContainer)
    {
        $calculator = $this->getDependencyContainer()->getOptionPriceToPayCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @ param OrderInterface $calculableContainer
     * @param CalculableInterface $calculableContainer
     */
    public function recalculateRemoveAllExpenses(CalculableInterface $calculableContainer)
    //public function recalculateRemoveAllExpenses(OrderInterface $calculableContainer)
    {
        $calculator = $this->getDependencyContainer()->getRemoveAllExpensesCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @ param OrderInterface $calculableContainer
     * @param CalculableInterface $calculableContainer
     */
    public function recalculateRemoveTotals(CalculableInterface $calculableContainer)
    //public function recalculateRemoveTotals(OrderInterface $calculableContainer)
    {
        $calculator = $this->getDependencyContainer()->getRemoveTotalsCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param OrderInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateSubtotalTotals(
        TotalsInterface $totalsTransfer,
        OrderInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $calculator = $this->getDependencyContainer()->getSubtotalTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param OrderInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateSubtotalWithoutItemExpensesTotals(
        TotalsInterface $totalsTransfer,
        OrderInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $calculator = $this->getDependencyContainer()->getSubtotalWithoutItemExpensesTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @param TotalsInterface $totalsTransfer
     * @param OrderInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateTaxTotals(
        TotalsInterface $totalsTransfer,
        OrderInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $calculator = $this->getDependencyContainer()->getTaxTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }
}
