<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\CalculableObjectTransfer;

class OfferGrandTotalCalculator implements OfferGrandTotalCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
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
