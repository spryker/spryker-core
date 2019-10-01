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
 * @method \Spryker\Zed\Oms\OmsConfig getConfig()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 */
class ReservationExportPlugin extends AbstractPlugin implements ReservationExportPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * Example plugin! Synchronous implementation. Change it according to implementation requirements.
     *
     * This is example plugin, here should be export to external storage, for example put message to queue, export to file. (serialize $reservationRequestTransfer)
     * The import would be done in other store which would read/consume exported message/file. Using importReservation facade method.
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
