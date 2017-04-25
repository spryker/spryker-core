<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Calculation\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
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
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function recalculateOrder(OrderTransfer $orderTransfer);

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
     *
     * Specification
     *  - Calculates item prices, based on store tax mode (gross/net)
     *  - Calculates item sum (gross/net) price
     *  - Calculate  item option sum (gross/net) price
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     */
    public function calculateItemPrice(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculates total item option amount uses ProductOption::getSumPrice()
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateProductOptionPriceAggregation(CalculableObjectTransfer $calculableObjectTransfer);


    /**
     * Specification:
     *  - Calculates item total discount amount without additions (options, item expenses)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     */
    public function calculateDiscountAmountAggregation(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculates item total discount amount with additions (options, item expenses)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     */
    public function calculateItemDiscountAmountFullAggregation(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculates item total tax amount with additions (options, item expenses)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     */
    public function calculateItemTaxAmountFullAggregation(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculates item sum aggregation with item and additions (options, item expenses)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     */
    public function calculateSumAggregation(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate item price to pay with additions (options, item expenses) after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     */
    public function calculatePriceToPayAggregation(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate order subtotal, sum of "setSumAggregation" for each item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     */
    public function calculateSubtotal(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate order expenses, sum of "sumPrice" for each order expense.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     */
    public function calculateExpenseTotal(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate total discount amount for given quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $CalculableObjectTransfer
     */
    public function calculateDiscountTotal(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate tax total sum all item taxes
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     */
    public function calculateTaxTotal(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate total refund amount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     */
    public function calculateRefundTotal(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate refundable amount for items and order expenses
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     */
    public function calculateRefundableAmount(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate grandTotal amount to pay after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     */
    public function calculateGrandTotal(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate tax amount for item and its additions (option, expenses) + order expenses
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     */
    public function calculateTaxAmount(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate total already canceled amount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateCanceledTotal(CalculableObjectTransfer $calculableObjectTransfer);


    /**
     * Specification:
     *  - Calculate tax average for item and expenses, used when recalculate taxable amount after refund
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateTaxRateAverageAggregation(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     *
     * Specification:
     * - Calculate tax amount after cancellation
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateTaxAfterCancellation(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     *
     * Specification:
     *  - Calculate total tax amount for order, take into account canceled amount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateOrderTaxTotal(CalculableObjectTransfer $calculableObjectTransfer);

}
