<?php


namespace Spryker\Zed\OfferGui\Dependency\Facade;


use Generated\Shared\Transfer\OfferTransfer;

interface OfferGuiToOfferFacadeInterface
{
    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferTransfer
     */
    public function getOfferById(OfferTransfer $offerTransfer): OfferTransfer;
}