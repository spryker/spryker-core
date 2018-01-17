<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Plugin\Oms\ReservationImport;

use Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\ReservationExportPluginInterface;

/**
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Oms\Communication\OmsCommunicationFactory getFactory()
 */
class ReservationExportPlugin extends AbstractPlugin implements ReservationExportPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * This is example plugin, here should be export to external storage, like queue, file etc.
     * While on other side you would read file/consume request and use OmsFacade::importReservation() facade method.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function export(OmsAvailabilityReservationRequestTransfer $reservationRequestTransfer)
    {
        $this->getFacade()->importReservation($reservationRequestTransfer);
    }
}
