<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Reader;

use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;

interface MerchantOrderReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer[]
     */
    public function getMerchantOrderItems(MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer): array;
}
