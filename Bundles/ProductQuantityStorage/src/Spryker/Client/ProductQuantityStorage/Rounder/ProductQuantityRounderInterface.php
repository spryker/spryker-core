<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Rounder;

use Generated\Shared\Transfer\ProductQuantityStorageTransfer;

interface ProductQuantityRounderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer $productQuantityStorageTransfer
     * @param float $quantity
     *
     * @return float
     */
    public function getNearestQuantity(ProductQuantityStorageTransfer $productQuantityStorageTransfer, float $quantity): float;
}
