<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Plugin\Oms\ReservationSynchronization;

use Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\ReservationSynchronizationPluginInterface;

/**
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Oms\Communication\OmsCommunicationFactory getFactory()
 */
class SynchronizeReservationWithStorePlugin extends AbstractPlugin implements ReservationSynchronizationPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
     *
     * @return void
     */
    public function synchronize(OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer)
    {
        $this->getFacade()->saveReservation($omsAvailabilityReservationRequestTransfer);
    }
}
