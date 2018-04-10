<?php


namespace Spryker\Zed\Offer\Business\Model;


use Generated\Shared\Transfer\CalculableObjectTransfer;

class OfferGrandTotalCalculator implements OfferGrandTotalCalculatorInterface
{
    /**
     * @param CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateGrandTotal(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $totalsTransfer = $calculableObjectTransfer->getTotals();

        $offerFeeAmount = (int)$calculableObjectTransfer->getOriginalQuote()->getOfferFee();

        $grandTotal = $totalsTransfer->getGrandTotal();
        $grandTotal += $offerFeeAmount;

        $totalsTransfer->setGrandTotal($grandTotal);
    }

}