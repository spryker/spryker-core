<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Authorizer;

use Generated\Shared\Transfer\MerchantUserTransfer;

interface MerchantUserAuthorizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return void
     */
    public function authorizeMerchantUser(MerchantUserTransfer $merchantUserTransfer): void;
}
