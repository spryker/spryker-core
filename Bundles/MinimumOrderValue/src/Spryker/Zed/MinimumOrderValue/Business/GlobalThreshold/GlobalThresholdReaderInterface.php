<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface GlobalThresholdReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer[]
     */
    public function getGlobalThresholdsByStoreAndCurrency(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array;
}
