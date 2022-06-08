<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OmsProductOfferReservation\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\OmsProductOfferReservationBuilder;
use Generated\Shared\Transfer\OmsProductOfferReservationTransfer;
use Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservation;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class OmsProductOfferReservationHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\OmsProductOfferReservationTransfer
     */
    public function haveOmsProductOfferReservation(array $seedData = []): OmsProductOfferReservationTransfer
    {
        $omsProductOfferReservationTransfer = (new OmsProductOfferReservationBuilder($seedData))->build();

        $omsProductOfferReservationEntity = (new SpyOmsProductOfferReservation())
            ->setFkStore($omsProductOfferReservationTransfer->getIdStore());

        $omsProductOfferReservationEntity->fromArray($omsProductOfferReservationTransfer->toArray());
        $omsProductOfferReservationEntity->save();

        $omsProductOfferReservationTransfer->setIdOmsProductOfferReservation($omsProductOfferReservationEntity->getIdOmsProductOfferReservation());

        $this->getDataCleanupHelper()->_addCleanup(function () use ($omsProductOfferReservationEntity): void {
            $omsProductOfferReservationEntity->delete();
        });

        return $omsProductOfferReservationTransfer;
    }
}
