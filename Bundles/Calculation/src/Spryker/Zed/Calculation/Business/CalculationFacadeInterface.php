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
     * Specification:
     *  - Maps Quote to CalculableObject
     *  - Run all calculator plugins
     *  - Maps CalculableObject to Quote
     *  - Return the updated quote
     *
     * @api
     *
     * @see CalculationConfig::getCalculatorStack
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculateQuote(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Run all calculator plugins
     *  - Return the updated order
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function recalculateOrder(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Remove totals from QuoteTransfer to recalculate in following calculator plugins (clear state)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function removeTotals(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Checks if the calculated totals in the quote are still valid/consistent.
     *  - If not valid then adds an error code and message to the response
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validateCheckoutGrandTotal(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);

    /**
     *
     * Specification:
     *  - Calculates item prices, based on store tax mode (gross/net)
     *  - Calculates item sum (gross/net) price
     *  - Calculate item option sum (gross/net) price
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
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
     *
     * @return void
     */
    public function calculateDiscountAmountAggregation(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculates item total discount amount with additions (options, item expenses)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
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
     * @return void
     */
    public function calculateItemTaxAmountFullAggregation(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculates item sum aggregation with item and additions (options, item expenses)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateSumAggregation(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate item price to pay with additions (options, item expenses) after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculatePriceToPayAggregation(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate order subtotal, sum of "setSumAggregation" for each item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateSubtotal(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate order expenses, sum of "sumPrice" for each order expense.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateExpenseTotal(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate total discount amount for given quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateDiscountTotal(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate tax total sum all item taxes
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateTaxTotal(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate total refund amount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateRefundTotal(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate refundable amount for items and order expenses
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateRefundableAmount(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate grandTotal amount to pay after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateGrandTotal(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate initial grandTotal amount (before discounts)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateInitialGrandTotal(CalculableObjectTransfer $calculableObjectTransfer);

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

    /**
     * Specification:
     *  - Loops over items and expense
     *  - Sets empty \ArrayObject for calculated discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function removeAllCalculatedDiscounts(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Loops over items
     *  - Sets to zero canceled amount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function removeCanceledAmount(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     *
     * Specification:
     *  - Calculates order total before taxes, net total.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateNetTotal(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculates discount amount for items and options, using generic discount amount field CalculateDiscountTransfer.unitAmount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateDiscountAmountAggregationForGenericAmount(CalculableObjectTransfer $calculableObjectTransfer);
}
