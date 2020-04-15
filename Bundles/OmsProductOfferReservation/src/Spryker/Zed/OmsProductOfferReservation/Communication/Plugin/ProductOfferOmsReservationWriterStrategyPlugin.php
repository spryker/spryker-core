<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Communication\Plugin;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationWriterStrategyPluginInterface;

/**
 * @method \Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservationFacadeInterface getFacade()
 * @method \Spryker\Zed\OmsProductOfferReservation\OmsProductOfferReservationConfig getConfig()
 */
class ProductOfferOmsReservationWriterStrategyPlugin extends AbstractPlugin implements OmsReservationWriterStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if ReservationRequestTransfer.productOfferReference not null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ReservationRequestTransfer $reservationRequestTransfer): bool
    {
        return $reservationRequestTransfer->getProductOfferReference() !== null;
    }

    /**
     * {@inheritDoc}
     * - Saves reserved quantity for provided ReservationRequestTransfer.productOfferReference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function saveReservation(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        $this->getFacade()->saveReservation($reservationRequestTransfer);
    }
}
