<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Calculation\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \Spryker\Zed\Calculation\Business\CalculationBusinessFactory getFactory()
 * @method \Spryker\Zed\Calculation\CalculationConfig getConfig()
 */
interface CalculationFacadeInterface
{

    /**
     * Executes all calculators in plugin stack.
     *
     * @api
     *
     * @see CalculationConfig::getCalculatorStack
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer);

    /**
     *
     * Specification
     *  - Calculates item prices, based on store tax mode (gross/net)
     *  - Calculates item sum (gross/net) price
     *  - Calculate  item option sum (gross/net) price
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateItemPrice(QuoteTransfer $quoteTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateExpenseGrossSumAmount(QuoteTransfer $quoteTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateExpenseTotals(QuoteTransfer $quoteTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateGrandTotalTotals(QuoteTransfer $quoteTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateItemGrossAmounts(QuoteTransfer $quoteTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateOptionGrossSum(QuoteTransfer $quoteTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function removeTotals(QuoteTransfer $quoteTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateSubtotalTotals(QuoteTransfer $quoteTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function validateCheckoutGrandTotal(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);

    /**
     * Specification:
     *  - Calculates total item option amount uses ProductOption::getSumPrice()
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateProductOptionPriceAggregation(QuoteTransfer $quoteTransfer);


    /**
     * Specification:
     *  - Calculates item total discount amount without additions (options, item expenses)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateItemDiscountAmountAggregation(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Calculates item total discount amount with additions (options, item expenses)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateItemDiscountAmountFullAggregation(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Calculates item total tax amount with additions (options, item expenses)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     */
    public function calculateItemTaxAmountFullAggregation(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Calculates item sum aggregation with item and additions (options, item expenses)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateItemSumAggregation(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Calculate item price to pay with additions (options, item expenses) after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateItemPriceToPayAggregation(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Calculate order subtotal, sum of "setSumAggregation" for each item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateSubtotal(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Calculate order expenses, sum of "sumPrice" for each order expense.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateExpenseTotal(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Calculate total discount amount for given quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateDiscountTotal(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Calculate tax total sum all item taxes
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateTaxTotal(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Calculate total refund amount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateRefundTotal(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Calculate refundable amount for items and order expenses
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateRefundableAmount(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Calculate grandTotal amount to pay after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function calculateGrandTotal(QuoteTransfer $quoteTransfer);

}
