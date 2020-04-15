<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Persistence;

use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Orm\Zed\OmsProductOfferReservation\Persistence\Map\SpyOmsProductOfferReservationTableMap;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationPersistenceFactory getFactory()
 */
class OmsProductOfferReservationRepository extends AbstractRepository implements OmsProductOfferReservationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getQuantity(
        OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
    ): Decimal {
        $quantity = $this->getFactory()->getOmsProductOfferReservationPropelQuery()
            ->filterByProductOfferReference($omsProductOfferReservationCriteriaTransfer->getProductOfferReference())
            ->filterByFkStore($omsProductOfferReservationCriteriaTransfer->getIdStore())
            ->select([SpyOmsProductOfferReservationTableMap::COL_RESERVATION_QUANTITY])
            ->findOne();

        if (!$quantity) {
            return new Decimal(0);
        }

        return new Decimal($quantity);
    }

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function saveReservation(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        $productOfferReservationEntity = $this->getFactory()->getOmsProductOfferReservationPropelQuery()
            ->filterByProductOfferReference($reservationRequestTransfer->getProductOfferReference())
            ->filterByFkStore($reservationRequestTransfer->getStore()->getIdStore())
            ->findOneOrCreate()
            ->setReservationQuantity($reservationRequestTransfer->getReservationQuantity());

        $productOfferReservationEntity->save();
    }
}
