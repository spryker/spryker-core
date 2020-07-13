<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantOrderTransfer;

/**
 * Allows to expand merchant order with additional data.
 */
interface MerchantOrderExpanderPluginInterface
{
    /**
     * Specification:
     * - Executes after a merchant order is retrieved.
     * - Expands merchant order with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function expand(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer;
}
