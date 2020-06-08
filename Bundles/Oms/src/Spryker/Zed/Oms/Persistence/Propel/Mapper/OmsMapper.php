<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OmsProductReservationTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservation;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationStore;

class OmsMapper
{
    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     * @param \Orm\Zed\Oms\Persistence\SpyOmsProductReservation $omsProductReservationEntity
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservation
     */
    public function mapReservationRequestTransferToOmsProductReservationEntity(
        ReservationRequestTransfer $reservationRequestTransfer,
        SpyOmsProductReservation $omsProductReservationEntity
    ): SpyOmsProductReservation {
        $omsProductReservationEntity->fromArray($reservationRequestTransfer->toArray());
        $omsProductReservationEntity->setFkStore($reservationRequestTransfer->getStore()->getIdStore());

        return $omsProductReservationEntity;
    }

    /**
     * @param \Orm\Zed\Oms\Persistence\SpyOmsProductReservation $omsProductReservationEntity
     * @param \Generated\Shared\Transfer\OmsProductReservationTransfer $omsProductReservationTransfer
     *
     * @return \Generated\Shared\Transfer\OmsProductReservationTransfer
     */
    public function mapOmsProductReservationEntityToOmsProductReservationTransfer(
        SpyOmsProductReservation $omsProductReservationEntity,
        OmsProductReservationTransfer $omsProductReservationTransfer
    ): OmsProductReservationTransfer {
        return $omsProductReservationTransfer->fromArray($omsProductReservationEntity->toArray());
    }

    /**
     * @param \Orm\Zed\Oms\Persistence\SpyOmsProductReservationStore $omsProductReservationStoreEntity
     * @param \Generated\Shared\Transfer\ReservationResponseTransfer $reservationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function mapOmsProductReservationStoreEntityToReservationResponseTransfer(
        SpyOmsProductReservationStore $omsProductReservationStoreEntity,
        ReservationResponseTransfer $reservationResponseTransfer
    ) {
        return $reservationResponseTransfer->setStoreName($omsProductReservationStoreEntity->getStore())
            ->setReservationQuantity($omsProductReservationStoreEntity->getReservationQuantity());
    }
}
