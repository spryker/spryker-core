<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\MerchantOrder;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface MerchantOrderWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function createMerchantOrder(
        OrderTransfer $orderTransfer,
        string $merchantReference
    ): MerchantOrderTransfer;
}
