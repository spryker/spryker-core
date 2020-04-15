<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Persistence;

use Generated\Shared\Transfer\OmsProductOfferReservationTransfer;

interface OmsProductOfferReservationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationTransfer $omsProductOfferReservationTransfer
     *
     * @return void
     */
    public function saveReservation(OmsProductOfferReservationTransfer $omsProductOfferReservationTransfer): void;
}
