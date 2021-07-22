<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Deleter;

use Generated\Shared\Transfer\ValidationResponseTransfer;

interface PriceDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param int $volumeQuantity
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function deletePrices(array $priceProductTransfers, int $volumeQuantity): ValidationResponseTransfer;
}
