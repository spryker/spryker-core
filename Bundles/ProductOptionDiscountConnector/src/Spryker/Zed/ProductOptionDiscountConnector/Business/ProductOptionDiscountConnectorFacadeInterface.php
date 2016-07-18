<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductOptionDiscountConnector\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \Spryker\Zed\ProductOptionDiscountConnector\Business\ProductOptionDiscountConnectorBusinessFactory getFactory()
 */
interface ProductOptionDiscountConnectorFacadeInterface
{

    /**
     * Specification:
     *  - Loops over product option calculated discounts and sums up to order total
     *  - Amounts stored in orderTransfer:calculatedDiscounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderCalculatedDiscounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Loops over product option discount with discount sum
     *  - Calculates totals with product options
     *  - Amounts stored: OrderTransfer->getTotals()->setDiscountTotal()
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalDiscountAmount(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Read order discounts from persistence
     *  - Assign discount to each coresponding item
     *  - Calculate item and product option discount amount fields with discount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemWithProductOptionsDiscounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Loops over items with discounts
     *  - Calculate discount amount for items after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemWithProductOptionsAndDiscountsTaxAmount(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Loops over items with options and discounts
     *  - Loops over expenses with discounts
     *  - Summ all tax amounts calculated
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalTaxAmountWithDiscounts(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Loops over items with options and discounts, calculates tax
     *  - Loops over expenses with discounts, calculates tax
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateItemWithProductOptionsAndDiscountsTaxAmount(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Loops over items with options and discounts
     *  - Loops over expenses with discounts
     *  - Summ all tax amounts calculated
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateOrderTotalTaxAmountWithDiscounts(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Read order discounts from persistence
     *  - Assign discount to each coresponding item
     *  - Calculate item and product option discount amount fields with discount
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateItemWithProductOptionsAndDiscountsGrossPrice(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Loops over product option discount with discount sum
     *  - Calculates totals with product options
     *  - Amounts stored: QuoteTransfer->getTotals()->setDiscountTotal()
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateDiscountTotalsWithProductOptions(QuoteTransfer $quoteTransfer);

}
