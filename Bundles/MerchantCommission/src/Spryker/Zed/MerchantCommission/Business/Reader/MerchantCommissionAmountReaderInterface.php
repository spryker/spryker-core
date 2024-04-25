<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Reader;

use Generated\Shared\Transfer\MerchantCommissionAmountCollectionTransfer;

interface MerchantCommissionAmountReaderInterface
{
    /**
     * @param list<int> $merchantCommissionIds
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionAmountCollectionTransfer
     */
    public function getMerchantCommissionAmountCollectionByMerchantCommissionIds(
        array $merchantCommissionIds
    ): MerchantCommissionAmountCollectionTransfer;
}
