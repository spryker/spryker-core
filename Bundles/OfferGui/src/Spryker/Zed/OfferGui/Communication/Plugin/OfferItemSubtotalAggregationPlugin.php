<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Plugin;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculationPluginInterface;

class OfferItemSubtotalAggregationPlugin implements CalculationPluginInterface
{
    //todo: move to BL
    //todo: move to Offer module
    //Guide: This calculator plugin must be placed under ItemSubtotalAggregatorPlugin
    //after the generic logic is executed
    /**
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $itemTransfers = $calculableObjectTransfer->getItems();

        foreach ($itemTransfers as $itemTransfer) {
            //apply offer discount
            if ($itemTransfer->getOfferDiscount() > 0) {
                $originUnitSubtotal = $itemTransfer->getUnitSubtotalAggregation();
                $calculatedDiscount = $originUnitSubtotal / 100 * $itemTransfer->getOfferDiscount();
                $calculatedUnitSubtotal = $originUnitSubtotal - $calculatedDiscount;
                $calculatedUnitSubtotal = (int)$calculatedUnitSubtotal;
                $itemTransfer->setUnitSubtotalAggregation($calculatedUnitSubtotal);

                $originUnitSubtotal = $itemTransfer->getSumSubtotalAggregation();
                $calculatedDiscount = $originUnitSubtotal / 100 * $itemTransfer->getOfferDiscount();
                $calculatedUnitSubtotal = $originUnitSubtotal - $calculatedDiscount;
                $calculatedUnitSubtotal = (int)$calculatedUnitSubtotal;
                $itemTransfer->setSumSubtotalAggregation($calculatedUnitSubtotal);
            }

            //apply fee
            $originUnitSubtotal = $itemTransfer->getUnitSubtotalAggregation();
            $calculatedUnitSubtotal = $originUnitSubtotal + $itemTransfer->getOfferFee();
            $calculatedUnitSubtotal = (int)$calculatedUnitSubtotal;
            $itemTransfer->setUnitSubtotalAggregation($calculatedUnitSubtotal);

            $originSumSubtotal = $itemTransfer->getSumSubtotalAggregation();
            $calculatedFeeSumSubtotal = $itemTransfer->getQuantity() * $itemTransfer->getOfferFee();
            $calculatedSumSubtotal = $originSumSubtotal + $calculatedFeeSumSubtotal;
            $calculatedSumSubtotal = (int)$calculatedSumSubtotal;
            $itemTransfer->setSumSubtotalAggregation($calculatedSumSubtotal);
        }
    }
}
