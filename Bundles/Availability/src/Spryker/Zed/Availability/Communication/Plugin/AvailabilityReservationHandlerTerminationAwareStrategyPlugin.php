<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Communication\Plugin;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationHandlerTerminationAwareStrategyPluginInterface;

/**
 * @method \Spryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface getFacade()
 * @method \Spryker\Zed\Availability\Communication\AvailabilityCommunicationFactory getFactory()
 */
class AvailabilityReservationHandlerTerminationAwareStrategyPlugin extends AbstractPlugin implements ReservationHandlerTerminationAwareStrategyPluginInterface
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
     * - Checks if request is applicable for concrete products.
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
     * - Updates availability for the concrete product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function handle(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        $this->getFacade()->updateAvailability($reservationRequestTransfer->getSku());
    }
}
