<?php

namespace Spryker\Zed\OfferGui\Communication\Plugin;


use Generated\Shared\Transfer\CalculableObjectTransfer;

class OfferItemSubtotalAggregationPlugin implements \Spryker\Zed\Calculation\Dependency\Plugin\CalculationPluginInterface
{
    //todo: move to BL
    //Guide: This calculator plugin must be placed under ItemSubtotalAggregatorPlugin
    //after the generic logic is executed
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
            $itemTransfer->setSumSubtotalAggregation($calculatedSumSubtotal);
        }
    }

}