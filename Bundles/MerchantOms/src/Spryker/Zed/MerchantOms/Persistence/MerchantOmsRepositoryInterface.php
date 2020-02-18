<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Persistence;

use Generated\Shared\Transfer\MerchantOmsProcessCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantOmsProcessTransfer;

interface MerchantOmsRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOmsProcessCriteriaFilterTransfer $merchantOmsProcessCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOmsProcessTransfer|null
     */
    public function findMerchantOmsProcess(MerchantOmsProcessCriteriaFilterTransfer $merchantOmsProcessCriteriaFilterTransfer): ?MerchantOmsProcessTransfer;
}
