<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Reader;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\ReturnTransfer;

interface MerchantOrderReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantSalesOrder(ReturnTransfer $returnTransfer): ?MerchantOrderTransfer;

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer[]
     */
    public function findMerchantOrderItems(ReturnTransfer $returnTransfer): array;
}
