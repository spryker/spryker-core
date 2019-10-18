<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Reservation;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

interface LeadProductReservationCalculatorInterface
{
    /**
     * @param string $leadProductSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateReservedAmountForLeadProduct(string $leadProductSku, StoreTransfer $storeTransfer): Decimal;
}
