<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantUserTransfer;

/**
 * Use this plugin to restrict login to the merchant portal.
 */
interface MerchantUserLoginRestrictionPluginInterface
{
    /**
     * Specification:
     * - Checks if the merchant user is restricted.
     * - Runs after merchant user data is loaded from the data source.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return bool
     */
    public function isRestricted(MerchantUserTransfer $merchantUserTransfer): bool;
}
