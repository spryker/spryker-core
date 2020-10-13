<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantOrderTransfer;

/**
 * Allows to modify merchant order transfer.
 */
interface MerchantOrderFilterPluginInterface
{
    /**
     * Specification:
     * - Executed when order transfer adding to merchant order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function filter(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer;
}
