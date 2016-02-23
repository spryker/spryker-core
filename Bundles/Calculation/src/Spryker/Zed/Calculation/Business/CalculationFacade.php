<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Calculation\Business\CalculationBusinessFactory getFactory()
 * @method \Spryker\Zed\Calculation\CalculationConfig getConfig()
 */
class CalculationFacade extends AbstractFacade implements CalculationFacadeInterface
{

    /**
     * Specification:
     * - Run all calculator plugins
     * - Return the updated quote
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createStackExecutor()->recalculate($quoteTransfer);
    }

    /**
     * Specific calculator
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateExpenseGrossSumAmount(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createExpenseGrossSumAmount()->recalculate($quoteTransfer);
    }

    /**
     * Specific calculator
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateExpenseTotals(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createExpenseTotalsCalculator()->recalculate($quoteTransfer);
    }

    /**
     * Specific calculator
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateGrandTotalTotals(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createGrandTotalsCalculator()->recalculate($quoteTransfer);
    }

    /**
     * Specific calculator
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateItemGrossAmounts(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createItemGrossSumCalculator()->recalculate($quoteTransfer);
    }

    /**
     * Specific calculator
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateOptionGrossSum(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createOptionGrossSumCalculator()->recalculate($quoteTransfer);
    }

    /**
     * Specific calculator
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function removeTotals(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createRemoveTotalsCalculator()->recalculate($quoteTransfer);
    }

    /**
     * Specific calculator
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateSubtotalTotals(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createSubtotalTotalsCalculator()->recalculate($quoteTransfer);
    }

    /**
     * Checks if the calculated totals in the quote are still valid/consistent.
     * If not: Adds an error code and message to the response
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
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
