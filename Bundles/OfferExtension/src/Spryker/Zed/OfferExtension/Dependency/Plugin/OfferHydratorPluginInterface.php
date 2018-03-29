<?php

namespace Spryker\Zed\OfferExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OfferTransfer;

interface OfferHydratorPluginInterface
{
    /**
     * Specification:
     * - Hydrates offer transfer fields with data
     *
     * @api
     *
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferTransfer
     */
    public function hydrateOffer(OfferTransfer $offerTransfer): OfferTransfer;
}