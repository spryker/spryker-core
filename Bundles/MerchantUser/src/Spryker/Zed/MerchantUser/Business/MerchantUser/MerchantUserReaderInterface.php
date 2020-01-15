<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\MerchantUser;

use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;

interface MerchantUserReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function getMerchantUser(MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer): ?MerchantUserTransfer;
}
