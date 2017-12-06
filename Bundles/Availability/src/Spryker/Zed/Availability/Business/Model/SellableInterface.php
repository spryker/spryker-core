<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\StoreTransfer;

interface SellableInterface
{
    /**
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity, StoreTransfer $storeTransfer = null);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return int
     */
    public function calculateStockForProduct($sku, StoreTransfer $storeTransfer = null);
}
