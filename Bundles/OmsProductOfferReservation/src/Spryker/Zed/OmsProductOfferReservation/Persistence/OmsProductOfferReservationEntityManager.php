<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Persistence;

use Generated\Shared\Transfer\OmsProductOfferReservationTransfer;
use Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservation;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationPersistenceFactory getFactory()
 */
class OmsProductOfferReservationEntityManager extends AbstractEntityManager implements OmsProductOfferReservationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationTransfer $omsProductOfferReservationTransfer
     *
     * @return void
     */
    public function create(OmsProductOfferReservationTransfer $omsProductOfferReservationTransfer): void
    {
        $productOfferReservationEntity = $this->getFactory()
            ->createOmsProductOfferReservationMapper()
            ->mapOmsProductOfferReservationTransferToOmsProductOfferReservationEntity(
                $omsProductOfferReservationTransfer,
                new SpyOmsProductOfferReservation()
            );

        $productOfferReservationEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationTransfer $omsProductOfferReservationTransfer
     *
     * @return void
     */
    public function update(OmsProductOfferReservationTransfer $omsProductOfferReservationTransfer): void
    {
        $productOfferReservationEntity = $this->getFactory()
            ->getOmsProductOfferReservationPropelQuery()
            ->filterByProductOfferReference($omsProductOfferReservationTransfer->getProductOfferReference())
            ->filterByFkStore($omsProductOfferReservationTransfer->getIdStore())
            ->findOne();

        $productOfferReservationEntity->setReservationQuantity($omsProductOfferReservationTransfer->getReservationQuantity());
        $productOfferReservationEntity->save();
    }
}
