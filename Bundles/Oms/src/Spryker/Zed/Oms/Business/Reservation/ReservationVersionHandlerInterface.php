<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Reservation;

use Generated\Shared\Transfer\StoreTransfer;

interface ReservationVersionHandlerInterface
{
    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return void
     */
    public function saveReservationVersion($sku, ?StoreTransfer $storeTransfer = null);
}
