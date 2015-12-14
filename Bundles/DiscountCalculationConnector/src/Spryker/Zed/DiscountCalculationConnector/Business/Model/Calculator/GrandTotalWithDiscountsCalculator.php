<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;

class GrandTotalWithDiscountsCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireTotals();

        $grandTotal = $quoteTransfer->getTotals()->getGrandTotal();
        $discountTotal = $this->getDiscountTotal($quoteTransfer);
        $grandTotal = $this->subtractDiscount($discountTotal, $grandTotal);

        $quoteTransfer->getTotals()->setGrandTotal($grandTotal);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getDiscountTotal(QuoteTransfer $quoteTransfer)
    {
        $discountTotalTransfer = $quoteTransfer->getTotals()->getDiscount();
        $discountTotal = 0;
        if ($discountTotalTransfer !== null) {
            $discountTotal = $discountTotalTransfer->getTotalAmount();
        }

        return $discountTotal;
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
