<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business;

use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\CalculationConfig;

/**
 * @method CalculationBusinessFactory getFactory()
 * @method CalculationConfig getConfig()
 */
class CalculationFacade extends AbstractFacade
{

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return CalculableInterface
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        $calculatorStack = $this->getFactory()->getConfig()->getCalculatorStack();

        return $this->getFactory()->getStackExecutor()->recalculate($calculatorStack, $calculableContainer);
    }

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return CalculableInterface
     */
    public function performSoftRecalculation(CalculableInterface $calculableContainer)
    {
        $calculatorStack = $this->getFactory()->getConfig()->getSoftCalculatorStack();

        return $this->getFactory()->getStackExecutor()->recalculate($calculatorStack, $calculableContainer);
    }

    /**
     * @param CalculableInterface $calculableContainer
     * @param null $calculableItems
     *
     * @return TotalsTransfer
     */
    public function recalculateTotals(
        CalculableInterface $calculableContainer,
        $calculableItems = null
    ) {
        $calculatorStack = $this->getFactory()->getConfig()->getCalculatorStack();

        return $this->getFactory()->getStackExecutor()->recalculateTotals(
            $calculatorStack,
            $calculableContainer,
            $calculableItems
        );
    }

    /**
     * @param CalculableInterface $calculableContainer
     * @param CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateExpensePriceToPay(CalculableInterface $calculableContainer)
    {
        $calculator = $this->getFactory()->getExpensePriceToPayCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param TotalsTransfer $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     *
     * @return void
     */
    public function recalculateExpenseTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $calculator = $this->getFactory()->getExpenseTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @param TotalsTransfer $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     *
     * @return void
     */
    public function recalculateGrandTotalTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $calculator = $this->getFactory()->getGrandTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateItemPriceToPay(CalculableInterface $calculableContainer)
    {
        $calculator = $this->getFactory()->getItemPriceToPayCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateOptionPriceToPay(CalculableInterface $calculableContainer)
    {
        $calculator = $this->getFactory()->getOptionPriceToPayCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateRemoveAllExpenses(CalculableInterface $calculableContainer)
    {
        $calculator = $this->getFactory()->getRemoveAllExpensesCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateRemoveTotals(CalculableInterface $calculableContainer)
    {
        $calculator = $this->getFactory()->getRemoveTotalsCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function calculateItemTotalPrice(CalculableInterface $calculableContainer)
    {
        $calculator = $this->getFactory()->getItemTotalCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @param TotalsTransfer $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     *
     * @return void
     */
    public function recalculateSubtotalTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $calculator = $this->getFactory()->getSubtotalTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @param TotalsTransfer $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     *
     * @return void
     */
    public function recalculateSubtotalWithoutItemExpensesTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $calculator = $this->getFactory()->getSubtotalWithoutItemExpensesTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @param TotalsTransfer $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     *
     * @return void
     */
    public function recalculateTaxTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $calculator = $this->getFactory()->getTaxTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

}
