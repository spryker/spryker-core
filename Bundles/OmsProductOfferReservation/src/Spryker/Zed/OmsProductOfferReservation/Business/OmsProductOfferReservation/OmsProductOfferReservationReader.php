<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservation;

use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;
use Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationRepositoryInterface;

class OmsProductOfferReservationReader implements OmsProductOfferReservationReaderInterface
{
    /**
     * @var \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationRepositoryInterface
     */
    protected $omsProductOfferReservationRepository;

    /**
     * @param \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationRepositoryInterface $omsProductOfferReservationRepository
     */
    public function __construct(OmsProductOfferReservationRepositoryInterface $omsProductOfferReservationRepository)
    {
        $this->omsProductOfferReservationRepository = $omsProductOfferReservationRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getQuantity(
        OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
    ): ReservationResponseTransfer {
        $omsProductOfferReservationCriteriaTransfer->requireProductOfferReference();
        $omsProductOfferReservationCriteriaTransfer->requireStore();

        $reservationQuantity = $this->omsProductOfferReservationRepository->getQuantity($omsProductOfferReservationCriteriaTransfer);

        return (new ReservationResponseTransfer())->setReservationQuantity($reservationQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return array
     */
    public function getAggregatedReservations(ReservationRequestTransfer $reservationRequestTransfer): array
    {
        $reservationRequestTransfer->requireProductOfferReference();
        $reservationRequestTransfer->requireReservedStates();

        return $this->omsProductOfferReservationRepository->getAggregatedReservations(
            $reservationRequestTransfer->getProductOfferReference(),
            $reservationRequestTransfer->getReservedStates()->getStates(),
            $reservationRequestTransfer->getStore()
        );
    }
}
