<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;

interface ProductMerchantPortalGuiToOmsFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getOmsReservedProductQuantity(ReservationRequestTransfer $reservationRequestTransfer): ReservationResponseTransfer;
}
