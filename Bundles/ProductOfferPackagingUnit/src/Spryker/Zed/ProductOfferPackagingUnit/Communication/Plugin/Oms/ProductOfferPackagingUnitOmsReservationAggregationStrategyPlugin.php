<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferPackagingUnit\Communication\Plugin\Oms;

use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationAggregationStrategyPluginInterface;

class ProductOfferPackagingUnitOmsReservationAggregationStrategyPlugin implements OmsReservationAggregationStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Check if item is offer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ReservationRequestTransfer $reservationRequestTransfer): bool
    {
        return !empty($reservationRequestTransfer->getItem()->getProductOfferReference());
    }

    /**
     * {@inheritDoc}
     * - Aggregates reservations for product offers packaging unit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return array
     */
    public function aggregateReservations(ReservationRequestTransfer $reservationRequestTransfer): array
    {
        return $this->getRepository()->getAggregatedReservations(
            $reservationRequestTransfer->getItem()->getProductOfferReference(),
            $reservationRequestTransfer->getReservedStates()->getStates(),
            $reservationRequestTransfer->getStore()
        );
    }
}
