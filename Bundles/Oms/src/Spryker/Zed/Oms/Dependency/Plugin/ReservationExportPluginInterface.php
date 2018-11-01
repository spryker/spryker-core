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
     * Specification:
     *  - This plugin is triggered when \Spryker\Zed\Oms\Communication\Console\ExportReservationConsole command is executed/
     *  - It will receive amount to be synchronized from sender store, receiver should either accept or ignore this request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function export(OmsAvailabilityReservationRequestTransfer $reservationRequestTransfer);
}
