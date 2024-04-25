<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Creator;

use Generated\Shared\Transfer\MerchantCommissionTransfer;

interface MerchantCommissionAmountCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function createMerchantCommissionAmounts(
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer;
}
