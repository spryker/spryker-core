<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Calculator\ItemGrossSumPriceCalculator;
use Spryker\Zed\Calculation\Business\Calculator\ItemNetSumPriceCalculator;
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
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createStackExecutor()->recalculate($quoteTransfer);
    }

    // START: new calculators

    /**
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateItemPrice(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->createPriceCalculator()
            ->recalculate($quoteTransfer);
    }


    /**
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateProductOptionPriceAggregation(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->createProductOptionPriceAggregator()
            ->recalculate($quoteTransfer);
    }


    /**
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateItemDiscountAmountAggregation(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->createItemDiscountAmountAggregator()
            ->recalculate($quoteTransfer);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateItemDiscountAmountFullAggregation(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->createItemDiscountAmountFullAggregator()
            ->recalculate($quoteTransfer);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     */
    public function calculateItemTaxAmountFullAggregation(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->createItemTaxAmountFullAggregator()
            ->recalculate($quoteTransfer);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateItemSumAggregation(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->createItemSumAggregator()
            ->recalculate($quoteTransfer);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateItemPriceToPayAggregation(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->createItemPriceToPayAggregator()
            ->recalculate($quoteTransfer);
    }


    // END: new calculators

    /**
     * Specific calculator
     *
     * @api
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
     * @api
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
     * @api
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
     * @api
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
     * @api
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
     * @api
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
     * @api
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
     * @api
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
            ->createCheckoutGrandTotalPreCondition()
            ->validateCheckoutGrandTotal($quoteTransfer, $checkoutResponseTransfer);
    }

}
