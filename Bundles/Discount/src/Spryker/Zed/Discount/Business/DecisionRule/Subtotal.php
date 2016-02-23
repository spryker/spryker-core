<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\RuleInterface;
use Spryker\Zed\Discount\Business\QueryString\AbstractComparableRule;

class Subtotal extends AbstractComparableRule implements RuleInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return float
     */
    protected function compareWith(QuoteTransfer $quoteTransfer)
    {
       $this->assertSubtotalRequirements($quoteTransfer);
       return (float) $quoteTransfer->getTotals()->getSubtotal();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertSubtotalRequirements(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();
        $quoteTransfer->getTotals()->requireSubtotal();
    }
}
