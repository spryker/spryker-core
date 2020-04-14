<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferPackagingUnit\Business;

use Generated\Shared\Transfer\ReservationRequestTransfer;

interface ProductOfferPackagingUnitFacadeInterface
{
    /**
     * Specification:
     * - Aggregates reservations for product offers.
     * - Required parameters in ReservationRequestTransfer: item, reservedStates.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function getAggregatedReservations(ReservationRequestTransfer $reservationRequestTransfer): array;
}
