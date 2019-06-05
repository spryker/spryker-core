<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface AvailabilityCartConnectorToAvailabilityInterface
{
    /**
     * @param string $sku
     * @param float $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity);

    /**
     * @param string $sku
     *
     * @return float
     */
    public function calculateStockForProduct($sku);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return float
     */
    public function calculateStockForProductWithStore($sku, StoreTransfer $storeTransfer);

    /**
     * @param string $sku
     * @param float $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore($sku, $quantity, StoreTransfer $storeTransfer);
}
