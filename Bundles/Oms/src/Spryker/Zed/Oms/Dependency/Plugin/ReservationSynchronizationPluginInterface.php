<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Dependency\Plugin;

use Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer;

interface ReservationSynchronizationPluginInterface
{
    /**
     * Specification:
     *  - Request to synchronize reservation for other store.
     *  - This is useful when stores shares stocks and you want to make sure it's updated in other stores also.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
     *
     * @return void
     */
    public function synchronize(OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer);
}
