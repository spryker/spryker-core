<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantOrderTransfer;

interface MerchantSalesOrderGuiToMerchantOmsFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     **/
    public function expandMerchantOrderItemsWithStateHistory(
        MerchantOrderTransfer $merchantOrderTransfer
    ): MerchantOrderTransfer;
}
