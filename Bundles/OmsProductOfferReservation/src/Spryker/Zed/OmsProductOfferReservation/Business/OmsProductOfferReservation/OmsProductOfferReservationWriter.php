<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservation;

use Generated\Shared\Transfer\OmsProductOfferReservationTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\Zed\OmsProductOfferReservation\Business\Mapper\OmsProductOfferReservationMapper;
use Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationEntityManagerInterface;
use Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationRepositoryInterface;

class OmsProductOfferReservationWriter implements OmsProductOfferReservationWriterInterface
{
    /**
     * @var \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationEntityManagerInterface
     */
    protected $omsProductOfferReservationEntityManager;

    /**
     * @var \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationRepositoryInterface
     */
    protected $omsProductOfferReservationRepository;

    /**
     * @var \Spryker\Zed\OmsProductOfferReservation\Business\Mapper\OmsProductOfferReservationMapper
     */
    protected $omsProductOfferReservationMapper;

    /**
     * @param \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationEntityManagerInterface $omsProductOfferReservationEntityManager
     * @param \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationRepositoryInterface $omsProductOfferReservationRepository
     * @param \Spryker\Zed\OmsProductOfferReservation\Business\Mapper\OmsProductOfferReservationMapper $omsProductOfferReservationMapper
     */
    public function __construct(
        OmsProductOfferReservationEntityManagerInterface $omsProductOfferReservationEntityManager,
        OmsProductOfferReservationRepositoryInterface $omsProductOfferReservationRepository,
        OmsProductOfferReservationMapper $omsProductOfferReservationMapper
    ) {
        $this->omsProductOfferReservationEntityManager = $omsProductOfferReservationEntityManager;
        $this->omsProductOfferReservationRepository = $omsProductOfferReservationRepository;
        $this->omsProductOfferReservationMapper = $omsProductOfferReservationMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function writeReservation(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        $omsProductOfferReservationTransfer = $this->omsProductOfferReservationRepository
            ->find($reservationRequestTransfer);

        if (!$omsProductOfferReservationTransfer) {
            $omsProductOfferReservationTransfer = $this->omsProductOfferReservationMapper
                ->mapReservationRequestTransferToOmsProductOfferReservationTransfer(
                    $reservationRequestTransfer,
                    new OmsProductOfferReservationTransfer(),
                );

            $this->omsProductOfferReservationEntityManager->create($omsProductOfferReservationTransfer);

            return;
        }

        $omsProductOfferReservationTransfer->setReservationQuantity($reservationRequestTransfer->getReservationQuantity());
        $this->omsProductOfferReservationEntityManager->update($omsProductOfferReservationTransfer);
    }
}
