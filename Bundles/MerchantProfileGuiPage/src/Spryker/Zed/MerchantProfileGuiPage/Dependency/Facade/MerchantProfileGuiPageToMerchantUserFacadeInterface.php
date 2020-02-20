<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade;

use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface MerchantProfileGuiPageToMerchantUserFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function findOne(
        MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
    ): ?MerchantUserTransfer;

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser(): UserTransfer;
}
