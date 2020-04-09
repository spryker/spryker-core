<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantOrderTransfer;

/**
 * Allows to add logic after merchant order with its items are created.
 */
interface MerchantOrderPostCreatePluginInterface
{
    /**
     * Specification:
     * - Executes after a merchant order with its items are created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return void
     */
    public function postCreate(MerchantOrderTransfer $merchantOrderTransfer): void;
}
