<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Business;

use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;

interface OmsProductOfferReservationFacadeInterface
{
    /**
     * Specification:
     * - Returns ReservationResponseTransfer with reservation quantity for product offer.
     * - Requires OmsProductOfferReservationCriteriaTransfer.productOfferReference.
     * - Requires OmsProductOfferReservationCriteriaTransfer.storeName.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getQuantityForProductOffer(
        OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
    ): ReservationResponseTransfer;
}
