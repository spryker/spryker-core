<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Offer\Zed;

use Generated\Shared\Transfer\OfferListTransfer;
use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Client\Offer\Dependency\Client\OfferToZedRequestClientInterface;

class OfferStub implements OfferStubInterface
{
    /**
     * @var \Spryker\Client\Offer\Dependency\Client\OfferToZedRequestClientInterface
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\Offer\Dependency\Client\OfferToZedRequestClientInterface $zedStub
     */
    public function __construct(OfferToZedRequestClientInterface $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function getOfferById(OfferTransfer $offerTransfer): OfferTransfer
    {
        return $this->zedStub->call('/offer/gateway/get-offer-by-id', $offerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OfferListTransfer $offerListTransfer
     *
     * @return \Generated\Shared\Transfer\OfferListTransfer
     */
    public function getOffers(OfferListTransfer $offerListTransfer): OfferListTransfer
    {
        return $this->zedStub->call('/offer/gateway/get-offers', $offerListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function placeOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        return $this->zedStub->call('/offer/gateway/place-offer', $offerTransfer);
    }
}
