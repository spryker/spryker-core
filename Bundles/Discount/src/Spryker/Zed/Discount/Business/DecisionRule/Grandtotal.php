<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\AbstractComparableRule;
use Spryker\Zed\Discount\Business\QueryString\RuleInterface;

class Grandtotal extends AbstractComparableRule implements RuleInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return float
     */
    protected function compareWith(QuoteTransfer $quoteTransfer)
    {
        $this->assertGrandTotalRequirements($quoteTransfer);
        return (float)$quoteTransfer->getTotals()->getGrandTotal();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertGrandTotalRequirements(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();
        $quoteTransfer->getTotals()->requireGrandTotal();
    }

}
