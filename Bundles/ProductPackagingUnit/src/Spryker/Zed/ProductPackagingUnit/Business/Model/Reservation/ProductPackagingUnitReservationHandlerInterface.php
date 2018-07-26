<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Reservation;

interface ProductPackagingUnitReservationHandlerInterface
{
    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateLeadProductReservation(string $sku): void;
}
