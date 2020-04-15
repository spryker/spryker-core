<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Communication\Plugin;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationAggregationStrategyPluginInterface;

/**
 * @method \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationRepositoryInterface getRepository()
 * @method \Spryker\Zed\OmsProductOfferReservation\OmsProductOfferReservationConfig getConfig()
 * @method \Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservationFacadeInterface getFacade()
 */
class ProductOfferOmsReservationAggregationStrategyPlugin extends AbstractPlugin implements OmsReservationAggregationStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if an item is a product offer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ReservationRequestTransfer $reservationRequestTransfer): bool
    {
        return (bool)$reservationRequestTransfer->getProductOfferReference();
    }

    /**
     * {@inheritDoc}
     * - Aggregates reservations for product offers.
     * - Requires ReservationRequestTransfer.productOfferReference transfer field to be set.
     * - Requires ReservationRequestTransfer.reservedStates transfer field to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function aggregateReservations(ReservationRequestTransfer $reservationRequestTransfer): array
    {
        return $this->getFacade()->getAggregatedReservations($reservationRequestTransfer);
    }
}
