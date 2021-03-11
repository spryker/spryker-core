<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Reader;

interface MerchantOrderItemReaderInterface
{
    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer[]
     */
    public function findMerchantOrderItems(array $salesOrderItemIds): array;
}
