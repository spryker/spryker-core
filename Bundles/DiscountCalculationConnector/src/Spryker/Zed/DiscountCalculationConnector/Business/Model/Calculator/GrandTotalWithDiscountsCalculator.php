<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;

class GrandTotalWithDiscountsCalculator implements CalculatorInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();

        $grandTotal = $quoteTransfer->getTotals()->getGrandTotal();
        $discountTotal = $quoteTransfer->getTotals()->getDiscountTotal();
        $grandTotal = $this->subtractDiscount($discountTotal, $grandTotal);

        $quoteTransfer->getTotals()->setGrandTotal($grandTotal);
    }

    /**
     * @param int $discountTotal
     * @param int $grandTotal
     *
     * @return int
     */
    protected function subtractDiscount($discountTotal, $grandTotal)
    {
        $grandTotal = $grandTotal - $discountTotal;

        if ($grandTotal < 0) {
            $grandTotal = 0;
        }

        return $grandTotal;
    }
}
