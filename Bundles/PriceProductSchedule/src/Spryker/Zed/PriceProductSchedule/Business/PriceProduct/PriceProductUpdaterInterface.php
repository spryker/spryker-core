<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProduct;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;

interface PriceProductUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $currentPriceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function updateCurrentPriceProduct(
        PriceProductTransfer $priceProductTransfer,
        PriceTypeTransfer $currentPriceType
    ): ?PriceProductTransfer;
}
