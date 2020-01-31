<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Group;

use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;

interface GroupAdderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param string $reference
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function addMerchantAdminToGroupByReference(MerchantUserTransfer $merchantUserTransfer, string $reference): MerchantUserResponseTransfer;
}
