<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Communication\Plugin;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationHandlerTerminationAwareStrategyPluginInterface;

/**
 * @method \Spryker\Zed\OmsProductOfferReservation\OmsProductOfferReservationConfig getConfig()
 * @method \Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservationFacadeInterface getFacade()
 */
class ProductOfferReservationHandlerTerminationAwareStrategyPlugin extends AbstractPlugin implements ReservationHandlerTerminationAwareStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return bool
     */
    public function isTerminated(ReservationRequestTransfer $reservationRequestTransfer): bool
    {
        return $reservationRequestTransfer->getProductOfferReference() !== null;
    }

    /**
     * {@inheritDoc}
     * - Checks if ReservationRequest.productOfferReference is not null.
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
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function handle(ReservationRequestTransfer $reservationRequestTransfer): void
    {
    }
}
