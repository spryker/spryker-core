<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * This plugin is triggered before recalculating the order totals during merchant order creation.
 */
interface MerchantOrderTotalsPreRecalculatePluginInterface
{
    /**
     * Specification:
     * - This plugin is called before recalculating the order totals.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function preRecalculate(
        OrderTransfer $orderTransfer,
        MerchantOrderTransfer $merchantOrderTransfer
    ): OrderTransfer;
}
