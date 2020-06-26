<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferPackagingUnit\Communication\Plugin\Oms;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationAggregationPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferPackagingUnit\Business\ProductOfferPackagingUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferPackagingUnit\ProductOfferPackagingUnitConfig getConfig()
 */
class ProductOfferPackagingUnitOmsReservationAggregationPlugin extends AbstractPlugin implements OmsReservationAggregationPluginInterface
{
    /**
     * {@inheritDoc}
     * - Aggregates reservations for product offers packaging unit.
     * - Requires ReservationRequestTransfer.sku transfer field to be set.
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
