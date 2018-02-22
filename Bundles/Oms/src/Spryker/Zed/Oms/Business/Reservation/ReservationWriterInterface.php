<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Reservation;

use Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer;

interface ReservationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
     *
     * @return void
     */
    public function saveReservationRequest(OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer);
}
