<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business\Creator;

use Generated\Shared\Transfer\OrderTransfer;

interface SalesMerchantCommissionCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function createSalesMerchantCommissions(OrderTransfer $orderTransfer): void;
}
