<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAppMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantUserTransfer;

interface MerchantAppMerchantPortalGuiToMerchantUserFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function getCurrentMerchantUser(): MerchantUserTransfer;
}
