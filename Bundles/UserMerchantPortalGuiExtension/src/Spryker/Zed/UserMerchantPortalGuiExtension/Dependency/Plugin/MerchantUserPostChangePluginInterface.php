<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantUserTransfer;

interface MerchantUserPostChangePluginInterface
{
    /**
     * Specification:
     *  - Plugin executed after merchant user data is changed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function execute(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer;
}
