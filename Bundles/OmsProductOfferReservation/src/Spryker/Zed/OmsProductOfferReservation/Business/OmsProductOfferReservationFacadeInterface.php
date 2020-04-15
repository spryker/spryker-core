<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Business;

use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;

interface OmsProductOfferReservationFacadeInterface
{
    /**
     * Specification:
     * - Returns ReservationResponseTransfer with reserved quantity for product offer.
     * - Requires OmsProductOfferReservationCriteriaTransfer.productOfferReference.
     * - Requires OmsProductOfferReservationCriteriaTransfer.idStore.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getQuantity(
        OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
    ): ReservationResponseTransfer;

    /**
     * Specification:
     * - Saves reservation data for provided ReservationRequestTransfer.item.
     * - Requires ReservationRequestTransfer.item.productOfferReference.
     * - Requires ReservationRequestTransfer.store.idStore.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function saveReservation(ReservationRequestTransfer $reservationRequestTransfer): void;
}
