<?php


namespace Spryker\Zed\Offer\Business\Model;


use Generated\Shared\Transfer\CalculableObjectTransfer;

interface OfferGrandTotalCalculatorInterface
{
    /**
     * @param CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateGrandTotal(CalculableObjectTransfer $calculableObjectTransfer): void;
}