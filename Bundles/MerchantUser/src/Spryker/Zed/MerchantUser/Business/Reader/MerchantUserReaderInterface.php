<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Reader;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;

interface MerchantUserReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function findOne(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): ?MerchantUserTransfer;
}
