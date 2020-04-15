<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Writer;

use Generated\Shared\Transfer\MerchantOrderItemResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;

interface MerchantOrderItemWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemResponseTransfer
     */
    public function update(MerchantOrderItemTransfer $merchantOrderItemTransfer): MerchantOrderItemResponseTransfer;
}
