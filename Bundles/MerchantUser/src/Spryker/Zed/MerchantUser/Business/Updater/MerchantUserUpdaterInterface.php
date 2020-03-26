<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Updater;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;

interface MerchantUserUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function update(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return void
     */
    public function disable(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): void;
}
