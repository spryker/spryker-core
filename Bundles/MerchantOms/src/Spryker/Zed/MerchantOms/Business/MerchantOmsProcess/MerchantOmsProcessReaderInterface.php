<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\MerchantOmsProcess;

use Generated\Shared\Transfer\MerchantOmsProcessCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantOmsProcessTransfer;

interface MerchantOmsProcessReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOmsProcessCriteriaFilterTransfer $merchantOmsProcessCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOmsProcessTransfer
     */
    public function getMerchantOmsProcess(MerchantOmsProcessCriteriaFilterTransfer $merchantOmsProcessCriteriaFilterTransfer): MerchantOmsProcessTransfer;
}
