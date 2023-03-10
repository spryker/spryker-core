<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Adder;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;

interface GroupAdderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param \Generated\Shared\Transfer\GroupTransfer $merchantUserGroupTransfer
     *
     * @return void
     */
    public function addMerchantUserToGroups(MerchantUserTransfer $merchantUserTransfer, GroupTransfer $merchantUserGroupTransfer): void;
}
