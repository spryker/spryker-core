<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \Spryker\Zed\DiscountCalculationConnector\Business\DiscountCalculationConnectorBusinessFactory getFactory()
 */
interface DiscountCalculationConnectorFacadeInterface
{

    /**
     * Specification:
     *  - Loops over items and expenses calculated discounts
     *  - Sums all calculated discounts and stores them to QuoteTransfer->getTotals()->setDiscountTotal()
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateDiscountTotals(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Loops over items and expense
     *  - Sets empty \ArrayObject for calculated discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function removeAllCalculatedDiscounts(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Takes grand total without discounts and subtract TotalDiscounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateGrandTotalWithDiscounts(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Loops over items and expenses
     *  - Calculates total item discount amount and gross price after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateSumGrossCalculatedDiscountAmount(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Calculates total item tax amount after discounts
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateExpenseTaxWithDiscounts(QuoteTransfer $quoteTransfer);

}
