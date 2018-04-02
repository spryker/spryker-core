<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Offer;

use Generated\Shared\Transfer\OfferListTransfer;
use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;

/**
 * @method \Spryker\Client\Offer\OfferFactory getFactory()
 */
interface OfferClientInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferListTransfer $offerListTransfer
     *
     * @return \Generated\Shared\Transfer\OfferListTransfer
     */
    public function getOffers(OfferListTransfer $offerListTransfer): OfferListTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function getOfferById(OfferTransfer $offerTransfer): OfferTransfer;

//    /**
//     * Specification:
//     * - Requests order to offer conversion.
//     *
//     * @api
//     *
//     * @param \Generated\Shared\Transfer\OfferToOrderConvertRequestTransfer $offerToOrderConvertRequestTransfer
//     *
//     * @return \Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer $offerToOrderRequestTransfer
//     */
//    public function convertOfferToOrder(OfferToOrderConvertRequestTransfer $offerToOrderConvertRequestTransfer): OfferToOrderConvertResponseTransfer;

    /**
     * todo:
     *
     * Specification:
     * - Places an offer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function placeOffer(OfferTransfer $offerTransfer): OfferResponseTransfer;
}
