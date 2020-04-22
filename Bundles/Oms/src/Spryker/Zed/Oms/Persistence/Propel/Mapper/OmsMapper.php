<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OmsProductReservationTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservation;

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
}
