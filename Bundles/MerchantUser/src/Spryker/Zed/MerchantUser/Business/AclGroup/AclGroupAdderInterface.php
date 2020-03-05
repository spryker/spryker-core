<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\AclGroup;

use Generated\Shared\Transfer\MerchantUserTransfer;

interface AclGroupAdderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param string $reference
     *
     * @throws \Spryker\Zed\MerchantUser\Business\Exception\AclGroupNotFoundException
     *
     * @return void
     */
    public function addMerchantAdminToGroup(MerchantUserTransfer $merchantUserTransfer, string $reference): void;
}
