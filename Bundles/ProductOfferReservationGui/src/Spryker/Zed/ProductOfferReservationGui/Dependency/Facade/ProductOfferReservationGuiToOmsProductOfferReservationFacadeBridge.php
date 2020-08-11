<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferReservationGui\Dependency\Facade;

use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;

class ProductOfferReservationGuiToOmsProductOfferReservationFacadeBridge implements ProductOfferReservationGuiToOmsProductOfferReservationFacadeInterface
{
    /**
     * @var \Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservationFacadeInterface
     */
    protected $omsProductOfferReservationFacade;

    /**
     * @param \Spryker\Zed\OmsProductOfferReservation\Business\OmsProductOfferReservationFacadeInterface $omsProductOfferReservationFacade
     */
    public function __construct($omsProductOfferReservationFacade)
    {
        $this->omsProductOfferReservationFacade = $omsProductOfferReservationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getQuantity(
        OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
    ): ReservationResponseTransfer {
        return $this->omsProductOfferReservationFacade->getQuantity($omsProductOfferReservationCriteriaTransfer);
    }
}
