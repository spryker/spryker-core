<?php

namespace Spryker\Zed\Offer\Dependency\Plugin;


use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;

interface OfferDoUpdatePluginInterface
{
    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferResponseTransfer
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferResponseTransfer;
}