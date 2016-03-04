<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business;

use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Calculation\Business\CalculationBusinessFactory getFactory()
 * @method \Spryker\Zed\Calculation\CalculationConfig getConfig()
 */
class CalculationFacade extends AbstractFacade implements CalculationFacadeInterface
{

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return \Spryker\Zed\Calculation\Business\Model\CalculableInterface
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        $calculatorStack = $this->getFactory()->getCalculatorStack();

        return $this->getFactory()->createStackExecutor()->recalculate($calculatorStack, $calculableContainer);
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|null $calculableItems
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    public function recalculateTotals(
        CalculableInterface $calculableContainer,
        $calculableItems = null
    ) {
        $calculatorStack = $this->getFactory()->getCalculatorStack();

        return $this->getFactory()->createStackExecutor()->recalculateTotals(
            $calculatorStack,
            $calculableContainer,
            $calculableItems
        );
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateExpensePriceToPay(CalculableInterface $calculableContainer)
    {
        $calculator = $this->getFactory()->createExpensePriceToPayCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateExpenseTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $calculator = $this->getFactory()->createExpenseTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateGrandTotalTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $calculator = $this->getFactory()->createGrandTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateItemPriceToPay(CalculableInterface $calculableContainer)
    {
        $calculator = $this->getFactory()->createItemPriceToPayCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateOptionPriceToPay(CalculableInterface $calculableContainer)
    {
        $calculator = $this->getFactory()->createOptionPriceToPayCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateRemoveAllExpenses(CalculableInterface $calculableContainer)
    {
        $calculator = $this->getFactory()->createRemoveAllExpensesCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateRemoveTotals(CalculableInterface $calculableContainer)
    {
        $calculator = $this->getFactory()->createRemoveTotalsCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function calculateItemTotalPrice(CalculableInterface $calculableContainer)
    {
        $calculator = $this->getFactory()->createItemTotalCalculator();
        $calculator->recalculate($calculableContainer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateSubtotalTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $calculator = $this->getFactory()->createSubtotalTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateSubtotalWithoutItemExpensesTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $calculator = $this->getFactory()->createSubtotalWithoutItemExpensesTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateTaxTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $calculator = $this->getFactory()->createTaxTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

}
