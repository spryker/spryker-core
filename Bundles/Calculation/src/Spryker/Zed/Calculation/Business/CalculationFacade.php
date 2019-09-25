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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Calculation\Business\CalculationBusinessFactory getFactory()
 * @method \Spryker\Zed\Calculation\CalculationConfig getConfig()
 */
class CalculationFacade extends AbstractFacade implements CalculationFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculateQuote(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createQuoteCalculatorExecutor()
            ->recalculate($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function recalculateOrder(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createOrderCalculatorExecutor()
            ->recalculate($orderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateItemPrice(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createPriceCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateProductOptionPriceAggregation(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createProductOptionPriceAggregator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateDiscountAmountAggregation(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createDiscountAmountAggregator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateDiscountAmountAggregationForGenericAmount(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createDiscountAmountAggregatorForGenericAmount()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateItemDiscountAmountFullAggregation(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createItemDiscountAmountFullAggregator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateItemTaxAmountFullAggregation(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createItemTaxAmountFullAggregator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateSumAggregation(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createSumAggregator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculatePriceToPayAggregation(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createPriceToPayAggregator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateSubtotal(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createSubtotalCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateExpenseTotal(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
           ->createExpenseTotalCalculator()
           ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateDiscountTotal(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createDiscountTotalCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateTaxTotal(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createTaxTotalCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateRefundTotal(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createRefundTotalCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateRefundableAmount(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createRefundableAmountCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateGrandTotal(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createGrandTotalCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateInitialGrandTotal(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createInitialGrandTotalCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateCanceledTotal(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createCanceledTotalCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateOrderTaxTotal(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createOrderTaxTotalCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function removeTotals(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createRemoveTotalsCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function removeAllCalculatedDiscounts(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createRemoveAllCalculatedDiscountsCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function removeCanceledAmount(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createRemoveCanceledAmountCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Will be removed in the next major version.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validateCheckoutGrandTotal(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        return $this->getFactory()
            ->createCheckoutGrandTotalPreCondition()
            ->validateCheckoutGrandTotal($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateNetTotal(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createNetTotalCalculator()
            ->recalculate($calculableObjectTransfer);
    }
}
