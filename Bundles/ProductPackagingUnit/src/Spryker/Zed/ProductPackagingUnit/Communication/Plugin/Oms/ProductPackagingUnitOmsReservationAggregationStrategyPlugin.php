<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Oms;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationAggregationStrategyPluginInterface;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnit\Communication\ProductPackagingUnitCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 */
class ProductPackagingUnitOmsReservationAggregationStrategyPlugin extends AbstractPlugin implements OmsReservationAggregationStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Always true for product packaging unit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ReservationRequestTransfer $reservationRequestTransfer): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     * - Aggregates reservations for product offers.
     * - Requires ReservationRequestTransfer.item, ReservationRequestTransfer.reservedStates transfer fields to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function aggregateReservations(ReservationRequestTransfer $reservationRequestTransfer): array
    {
        $reservationRequestTransfer->requireItem();
        $reservationRequestTransfer->requireReservedStates();

        return $this->getFacade()->aggregateProductPackagingUnitReservation(
            $reservationRequestTransfer->getItem()->getSku(),
            $reservationRequestTransfer->getReservedStates(),
            $reservationRequestTransfer->getStore()
        );
    }
}
