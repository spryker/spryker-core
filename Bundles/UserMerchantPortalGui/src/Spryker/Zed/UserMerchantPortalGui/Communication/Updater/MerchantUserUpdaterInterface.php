<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Updater;

use Generated\Shared\Transfer\MerchantUserTransfer;

interface MerchantUserUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return void
     */
    public function updateMerchantUser(MerchantUserTransfer $merchantUserTransfer): void;

    /**
     * @param string $newPassword
     *
     * @throws \Spryker\Zed\UserMerchantPortalGui\Communication\Exception\MerchantUserNotFoundException
     *
     * @return void
     */
    public function updateCurrentMerchantUserPassword(string $newPassword): void;
}
