<?php

namespace Spryker\Zed\Offer\Business\Model\Hydrator;

use Generated\Shared\Transfer\OfferTransfer;

interface OfferSavingAmountHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrate(OfferTransfer $offerTransfer): OfferTransfer;
}