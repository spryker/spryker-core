<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProduct;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface PriceProductUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $currentPriceType
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function updateCurrentPriceProduct(
        PriceProductTransfer $priceProductTransfer,
        PriceTypeTransfer $currentPriceType,
        StoreTransfer $storeTransfer
    ): ?PriceProductTransfer;
}
