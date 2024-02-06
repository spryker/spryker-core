<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;

/**
 * Use this plugin to expand `MerchantUserCriteriaTransfer` to find a merchant user for security user creation.
 */
interface MerchantUserCriteriaExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `MerchantUserCriteriaTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserCriteriaTransfer
     */
    public function expand(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): MerchantUserCriteriaTransfer;
}
