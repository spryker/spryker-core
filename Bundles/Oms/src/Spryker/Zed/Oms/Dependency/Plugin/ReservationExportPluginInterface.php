<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Dependency\Plugin;

use Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer;

interface ReservationExportPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function export(OmsAvailabilityReservationRequestTransfer $reservationRequestTransfer);
}
