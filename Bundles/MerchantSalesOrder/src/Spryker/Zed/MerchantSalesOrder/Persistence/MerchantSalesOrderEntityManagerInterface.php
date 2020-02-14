<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Persistence;

use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

interface MerchantSalesOrderEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function createMerchantSalesOrder(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer
     */
    public function createMerchantSalesOrderItem(
        MerchantOrderItemTransfer $merchantOrderItemTransfer
    ): MerchantOrderItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    public function createMerchantSalesOrderTotals(TotalsTransfer $totalsTransfer): TotalsTransfer;
}
