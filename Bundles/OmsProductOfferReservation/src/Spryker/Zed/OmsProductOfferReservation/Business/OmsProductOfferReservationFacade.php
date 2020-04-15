<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Business;

use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservationBusinessFactory getFactory()()
 * @method \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationRepositoryInterface getRepository()
 */
class OmsProductOfferReservationFacade extends AbstractFacade implements OmsProductOfferReservationFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getQuantity(
        OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
    ): ReservationResponseTransfer {
        return $this->getFactory()->createOmsProductOfferReservationReader()->getQuantity($omsProductOfferReservationCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function getAggregatedReservations(ReservationRequestTransfer $reservationRequestTransfer): array
    {
        $reservationRequestTransfer->requireProductOfferReference();
        $reservationRequestTransfer->requireReservedStates();

        return $this->getRepository()->getAggregatedReservations(
            $reservationRequestTransfer->getProductOfferReference(),
            $reservationRequestTransfer->getReservedStates()->getStates(),
            $reservationRequestTransfer->getStore()
        );
    }
}
