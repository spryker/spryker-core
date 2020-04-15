<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservation;

use Generated\Shared\Transfer\OmsProductOfferReservationTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\Zed\OmsProductOfferReservation\Business\Mapper\OmsProductOfferReservationBusinessMapper;
use Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationEntityManagerInterface;

class OmsProductOfferReservationWriter implements OmsProductOfferReservationWriterInterface
{
    /**
     * @var \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationEntityManagerInterface
     */
    protected $omsProductOfferReservationEntityManager;

    /**
     * @var \Spryker\Zed\OmsProductOfferReservation\Business\Mapper\OmsProductOfferReservationBusinessMapper
     */
    protected $omsProductOfferReservationBusinessMapper;

    /**
     * @param \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationEntityManagerInterface $omsProductOfferReservationEntityManager
     * @param \Spryker\Zed\OmsProductOfferReservation\Business\Mapper\OmsProductOfferReservationBusinessMapper $omsProductOfferReservationBusinessMapper
     */
    public function __construct(
        OmsProductOfferReservationEntityManagerInterface $omsProductOfferReservationEntityManager,
        OmsProductOfferReservationBusinessMapper $omsProductOfferReservationBusinessMapper
    ) {
        $this->omsProductOfferReservationEntityManager = $omsProductOfferReservationEntityManager;
        $this->omsProductOfferReservationBusinessMapper = $omsProductOfferReservationBusinessMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function saveReservation(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        $reservationRequestTransfer->requireProductOfferReference();
        $reservationRequestTransfer->requireStore()->getStore()->requireIdStore();
        $reservationRequestTransfer->requireReservationQuantity();

        $omsProductOfferReservationTransfer = $this->omsProductOfferReservationBusinessMapper
            ->mapReservationRequestTransferToOmsProductOfferReservationTransfer(
                $reservationRequestTransfer,
                new OmsProductOfferReservationTransfer()
            );

        $this->omsProductOfferReservationEntityManager->saveReservation($omsProductOfferReservationTransfer);
    }
}
