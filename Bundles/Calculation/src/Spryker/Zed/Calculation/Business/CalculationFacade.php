<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Calculation\CalculationConfig;

/**
 * @method CalculationBusinessFactory getFactory()
 * @method CalculationConfig getConfig()
 */
class CalculationFacade extends AbstractFacade
{

    /**
     * Executes all calculators in plugin stack.
     *
     * @see CalculationConfig::getCalculatorStack
     *
     * @param QuoteTransfer $quoteTransfer
     *
     * @return QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createStackExecutor()->recalculate($quoteTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateExpenseGrossSumAmount(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createExpenseGrossSumAmount()->recalculate($quoteTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateExpenseTotals(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createExpenseTotalsCalculator()->recalculate($quoteTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateGrandTotalTotals(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createGrandTotalsCalculator()->recalculate($quoteTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateItemGrossAmounts(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createItemGrossSumCalculator()->recalculate($quoteTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateOptionGrossSum(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createOptionGrossSumCalculator()->recalculate($quoteTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function removeTotals(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createRemoveTotalsCalculator()->recalculate($quoteTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateSubtotalTotals(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createSubtotalTotalsCalculator()->recalculate($quoteTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function validateCheckoutGrandTotal(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $this->getFactory()
            ->createCheckoutGrandTotalPrecondition()
            ->validateCheckoutGrandTotal($quoteTransfer, $checkoutResponseTransfer);
    }

}
