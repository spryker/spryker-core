<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Persistence;

use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;
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
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getQuantityForProductOffer(
        OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
    ): ReservationResponseTransfer {
        $omsProductOfferReservationCriteriaTransfer->requireProductOfferReference();
        $omsProductOfferReservationCriteriaTransfer->requireStoreName();

        $quantity = $this->getFactory()->createPropelOmsProductOfferReservationQuery()
            ->filterByProductOfferReference($omsProductOfferReservationCriteriaTransfer->getProductOfferReference())
            ->useStoreQuery()
                ->filterByName($omsProductOfferReservationCriteriaTransfer->getStoreName())
            ->endUse()
            ->select([SpyOmsProductOfferReservationTableMap::COL_RESERVATION_QUANTITY])
            ->findOne();

        $reservationResponseTransfer = new ReservationResponseTransfer();

        if (!$quantity) {
            return $reservationResponseTransfer->setReservationQuantity(new Decimal(0));
        }

        return $reservationResponseTransfer->setReservationQuantity(new Decimal($quantity));
    }
}
