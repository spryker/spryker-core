<?php


namespace Spryker\Zed\Offer\Business\Model;


use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;

interface OfferWriterInterface
{
    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferResponseTransfer
     */
    public function placeOffer(OfferTransfer $offerTransfer): OfferResponseTransfer;

    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferResponseTransfer
     *
     * @throws \Exception
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferResponseTransfer;
}