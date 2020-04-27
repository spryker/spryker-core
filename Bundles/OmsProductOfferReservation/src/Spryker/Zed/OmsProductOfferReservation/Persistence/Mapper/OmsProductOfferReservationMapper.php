<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Persistence\Mapper;

use Generated\Shared\Transfer\OmsProductOfferReservationTransfer;
use Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservation;

class OmsProductOfferReservationMapper
{
    /**
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationTransfer $omsProductOfferReservationTransfer
     * @param \Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservation $omsProductOfferReservationEntity
     *
     * @return \Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservation
     */
    public function mapOmsProductOfferReservationTransferToOmsProductOfferReservationEntity(
        OmsProductOfferReservationTransfer $omsProductOfferReservationTransfer,
        SpyOmsProductOfferReservation $omsProductOfferReservationEntity
    ): SpyOmsProductOfferReservation {
        $omsProductOfferReservationEntity->fromArray($omsProductOfferReservationTransfer->modifiedToArray());

        return $omsProductOfferReservationEntity->setFkStore(
            $omsProductOfferReservationTransfer->getIdStore()
        );
    }

    /**
     * @param \Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservation $omsProductOfferReservationEntity
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationTransfer $omsProductOfferReservationTransfer
     *
     * @return \Generated\Shared\Transfer\OmsProductOfferReservationTransfer
     */
    public function mapOmsProductOfferReservationEntityToOmsProductOfferReservationTransfer(
        SpyOmsProductOfferReservation $omsProductOfferReservationEntity,
        OmsProductOfferReservationTransfer $omsProductOfferReservationTransfer
    ): OmsProductOfferReservationTransfer {
        return $omsProductOfferReservationTransfer->fromArray($omsProductOfferReservationEntity->toArray(), true)
            ->setIdStore($omsProductOfferReservationEntity->getFkStore());
    }
}
