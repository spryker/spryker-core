<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\Offer\Dependency\Service\OfferToUtilProductServiceInterface;

class OfferItemSubtotalAggregator implements OfferItemSubtotalAggregatorInterface
{
    /**
     * @var \Spryker\Zed\Offer\Dependency\Service\OfferToUtilProductServiceInterface
     */
    protected $utilProductService;

    /**
     * @param \Spryker\Zed\Offer\Dependency\Service\OfferToUtilProductServiceInterface $utilProductService
     */
    public function __construct(OfferToUtilProductServiceInterface $utilProductService)
    {
        $this->utilProductService = $utilProductService;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $itemTransfers = $calculableObjectTransfer->getItems();

        foreach ($itemTransfers as $itemTransfer) {
            //apply offer discount
            if ($itemTransfer->getOfferDiscount() > 0) {
                $originUnitSubtotal = $itemTransfer->getUnitSubtotalAggregation();
                $calculatedDiscount = $originUnitSubtotal / 100 * $itemTransfer->getOfferDiscount();
                $calculatedUnitSubtotal = $originUnitSubtotal - $calculatedDiscount;
                $calculatedUnitSubtotal = $this->roundUnitSubtotalAggregation($calculatedUnitSubtotal);
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
            $itemTransfer->setUnitSubtotalAggregation($calculatedUnitSubtotal);

            $originSumSubtotal = $itemTransfer->getSumSubtotalAggregation();
            $calculatedFeeSumSubtotal = $itemTransfer->getQuantity() * $itemTransfer->getOfferFee();
            $calculatedSumSubtotal = $originSumSubtotal + $calculatedFeeSumSubtotal;
            $calculatedSumSubtotal = $this->roundSumSubtotalAggregation($calculatedSumSubtotal);

            $itemTransfer->setSumSubtotalAggregation($calculatedSumSubtotal);
        }
    }

    /**
     * @param float $unitSubtotalAggregation
     *
     * @return int
     */
    protected function roundUnitSubtotalAggregation(float $unitSubtotalAggregation): int
    {
        return $this->utilProductService->roundPrice($unitSubtotalAggregation);
    }

    /**
     * @param float $sumSubtotalAggregation
     *
     * @return int
     */
    protected function roundSumSubtotalAggregation(float $sumSubtotalAggregation): int
    {
        return $this->utilProductService->roundPrice($sumSubtotalAggregation);
    }
}
