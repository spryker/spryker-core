<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Reservation;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationHandlerTerminationAwareStrategyPluginInterface;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 * @method \Spryker\Zed\ProductPackagingUnit\Communication\ProductPackagingUnitCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface getFacade()
 */
class LeadProductReservationHandlerTerminationAwareStrategyPlugin extends AbstractPlugin implements ReservationHandlerTerminationAwareStrategyPluginInterface
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
        return false;
    }

    /**
     * {@inheritDoc}
     * - Checks if ReservationRequest.sku is not null;
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ReservationRequestTransfer $reservationRequestTransfer): bool
    {
        return $reservationRequestTransfer->getSku() !== null;
    }

    /**
     * {@inheritDoc}
     * - Updates the lead product's reservation for the provided product packaging unit SKU.
     * - Skips updating if the product packaging unit has self as lead product.
     * - Oms Reservation plugins is expected to update availability.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function handle(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        $this->getFacade()->updateLeadProductReservation($reservationRequestTransfer->getSku());
    }
}
