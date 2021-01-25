<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservation;

use Generated\Shared\Transfer\ReservationRequestTransfer;

interface OmsProductOfferReservationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function writeReservation(ReservationRequestTransfer $reservationRequestTransfer): void;
}
