<?php

namespace Spryker\Zed\Customer\Business\Model\Hydrator;

use Generated\Shared\Transfer\OfferTransfer;

interface OfferCustomerHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrate(OfferTransfer $offerTransfer): OfferTransfer;
}