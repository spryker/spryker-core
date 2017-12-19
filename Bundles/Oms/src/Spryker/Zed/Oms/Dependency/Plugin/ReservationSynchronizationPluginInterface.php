<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Dependency\Plugin;

use Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer;

interface ReservationSynchronizationPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
     *
     * @return void
     */
    public function synchronize(OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer);
}
