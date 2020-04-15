<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Generated\Shared\Transfer\ReservationRequestTransfer;
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
    public function saveReservation(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        $reservationRequestTransfer->requireSku();
        $reservationRequestTransfer->requireStore();
        $storeTransfer = $reservationRequestTransfer->getStore();

        $reservationEntity = $this->getFactory()->createOmsProductReservationQuery()
            ->filterBySku($reservationRequestTransfer->getSku())
            ->filterByFkStore($storeTransfer->requireIdStore()->getIdStore())
            ->findOneOrCreate();

        $reservationEntity->setReservationQuantity($reservationRequestTransfer->getReservationQuantity());
        $reservationEntity->save();
    }
}
