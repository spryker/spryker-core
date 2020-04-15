<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Business\Mapper;

use Generated\Shared\Transfer\OmsProductOfferReservationTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;

class OmsProductOfferReservationBusinessMapper
{
    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationTransfer $omsProductOfferReservationTransfer
     *
     * @return \Generated\Shared\Transfer\OmsProductOfferReservationTransfer
     */
    public function mapReservationRequestTransferToOmsProductOfferReservationTransfer(
        ReservationRequestTransfer $reservationRequestTransfer,
        OmsProductOfferReservationTransfer $omsProductOfferReservationTransfer
    ): OmsProductOfferReservationTransfer {
        return $omsProductOfferReservationTransfer->setReservationQuantity($reservationRequestTransfer->getReservationQuantity())
            ->setIdStore($reservationRequestTransfer->getStore()->getIdStore())
            ->setProductOfferReference($reservationRequestTransfer->getProductOfferReference());
    }
}
