<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\ReservationRequestTransfer;

interface ProductPackagingUnitToOmsFacadeInterface
{
    /**
     * @deprecated Use `\Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeInterface::updateReservation()` instead.` instead.
     *
     * @param string $sku
     *
     * @return void
     */
    public function updateReservationQuantity(string $sku): void;

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function updateReservation(ReservationRequestTransfer $reservationRequestTransfer): void;
}
