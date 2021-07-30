<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantUserTransfer;

/**
 * Executes after MerchantUser is created.
 */
interface MerchantUserPostCreatePluginInterface
{
    /**
     * Specification:
     * - Executes after MerchantUser is created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function postCreate(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer;
}
