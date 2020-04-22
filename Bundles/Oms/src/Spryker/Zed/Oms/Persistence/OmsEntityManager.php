<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Generated\Shared\Transfer\OmsProductReservationTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservation;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Oms\Persistence\OmsPersistenceFactory getFactory()
 */
class OmsEntityManager extends AbstractEntityManager implements OmsEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function createReservation(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        $reservationRequestTransfer->requireSku();
        $reservationRequestTransfer->requireStore();

        $omsProductReservationEntity = $this->getFactory()
            ->createOmsMapper()
            ->mapReservationRequestTransferToOmsProductReservationEntity(
                $reservationRequestTransfer,
                new SpyOmsProductReservation()
            );

        $omsProductReservationEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\OmsProductReservationTransfer $omsProductReservationTransfer
     *
     * @return void
     */
    public function updateReservation(OmsProductReservationTransfer $omsProductReservationTransfer): void
    {
        $omsProductReservationEntity = $this->getFactory()
            ->createOmsMapper()
            ->mapOmsProductReservationTransferToOmsProductReservationEntity(
                $omsProductReservationTransfer,
                new SpyOmsProductReservation()
            );

        $omsProductReservationEntity->save();
    }
}
