<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderItem;

use Generated\Shared\Transfer\MerchantOrderItemTransfer;

interface MerchantOrderItemWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer
     */
    public function update(MerchantOrderItemTransfer $merchantOrderItemTransfer): MerchantOrderItemTransfer;
}
