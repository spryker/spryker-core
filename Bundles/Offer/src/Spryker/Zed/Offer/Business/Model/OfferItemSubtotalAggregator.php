<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class OfferItemSubtotalAggregator implements OfferItemSubtotalAggregatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $this->calculateItemUnitSubtotalAggregation($itemTransfer);
            $this->calculateItemSumSubtotalAggregation($itemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function calculateItemUnitSubtotalAggregation(ItemTransfer $itemTransfer): void
    {
        $unitSubtotal = $itemTransfer->getUnitSubtotalAggregation();
        if ($itemTransfer->getOfferDiscount() > 0) {
            $calculatedDiscount = (int)($unitSubtotal / 100 * $itemTransfer->getOfferDiscount());
            $unitSubtotal = $unitSubtotal - $calculatedDiscount;
        }
        $unitSubtotal = $unitSubtotal + $itemTransfer->getOfferFee();
        $itemTransfer->setUnitSubtotalAggregation($unitSubtotal);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function calculateItemSumSubtotalAggregation(ItemTransfer $itemTransfer): void
    {
        $sumSubtotal = $itemTransfer->getSumSubtotalAggregation();
        if ($itemTransfer->getOfferDiscount() > 0) {
            $calculatedDiscount = (int)($sumSubtotal / 100 * $itemTransfer->getOfferDiscount());
            $sumSubtotal = $sumSubtotal - $calculatedDiscount;
        }
        $sumSubtotal = $sumSubtotal + $itemTransfer->getQuantity() * $itemTransfer->getOfferFee();
        $itemTransfer->setSumSubtotalAggregation($sumSubtotal);
    }
}
