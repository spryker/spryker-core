<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Offer\Zed;

use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferToOrderConvertRequestTransfer;
use Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer;
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
     * @param \Generated\Shared\Transfer\OfferToOrderConvertRequestTransfer $offerToOrderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer
     */
    public function convertOfferToOrder(OfferToOrderConvertRequestTransfer $offerToOrderRequestTransfer): OfferToOrderConvertResponseTransfer
    {
        return $this->zedStub->call('/offer/gateway/convert-offer-to-order', $offerToOrderRequestTransfer);
    }

    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferResponseTransfer
     */
    public function placeOffer(OfferTransfer $offerTransfer): OfferResponseTransfer
    {
        return $this->zedStub->call('/offer/gateway/place-offer', $offerTransfer);
    }
}
