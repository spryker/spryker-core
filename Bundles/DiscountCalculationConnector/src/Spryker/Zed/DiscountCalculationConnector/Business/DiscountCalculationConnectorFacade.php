<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DiscountCalculationConnector\Business\DiscountCalculationConnectorBusinessFactory getFactory()
 */
class DiscountCalculationConnectorFacade extends AbstractFacade implements DiscountCalculationConnectorFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateDiscountTotals(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createDiscountTotalsCalculator()->recalculate($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function removeAllCalculatedDiscounts(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createRemoveAllCalculatedDiscountsCalculator()->recalculate($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateGrandTotalWithDiscounts(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createGrandTotalWithDiscountsCalculator()->recalculate($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateSumGrossCalculatedDiscountAmount(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createSumGrossCalculatedDiscountAmountCalculator()->recalculate($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateExpenseTaxWithDiscounts(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createExpenseTaxWithDiscountsCalculator()->recalculate($quoteTransfer);
    }

}
