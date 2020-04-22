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
     * - Requires OmsProductOfferReservationCriteriaTransfer.productOfferReference transfer field to be set.
     * - Requires OmsProductOfferReservationCriteriaTransfer.idStore transfer field to be set.
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
     * - Aggregates reservations for product offers.
     * - Requires ReservationRequestTransfer.productOfferReference transfer field to be set.
     * - Requires ReservationRequestTransfer.reservedStates transfer field to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function getAggregatedReservations(ReservationRequestTransfer $reservationRequestTransfer): array;

    /**
     * Specification:
     * - Saves reservation quantity for provided ReservationRequestTransfer.
     * - Requires ReservationRequestTransfer.productOfferReference transfer field to be set.
     * - Requires ReservationRequestTransfer.store.idStore transfer field to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function writeReservation(ReservationRequestTransfer $reservationRequestTransfer): void;
}
