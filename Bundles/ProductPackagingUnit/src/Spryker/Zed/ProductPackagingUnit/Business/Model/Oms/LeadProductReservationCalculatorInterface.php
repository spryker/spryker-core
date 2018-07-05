<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Oms;

use Generated\Shared\Transfer\StoreTransfer;

interface LeadProductReservationCalculatorInterface
{
    /**
     * @param string $leadProductSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function calculateStockForLeadProduct(string $leadProductSku, StoreTransfer $storeTransfer): int;
}
