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

interface OfferStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\OfferToOrderConvertRequestTransfer $offerToOrderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer
     */
    public function convertOfferToOrder(OfferToOrderConvertRequestTransfer $offerToOrderRequestTransfer): OfferToOrderConvertResponseTransfer;

    /**
     * @param OfferTransfer $offerTransfer
     *
     * @return OfferResponseTransfer
     */
    public function placeOffer(OfferTransfer $offerTransfer): OfferResponseTransfer;
}
