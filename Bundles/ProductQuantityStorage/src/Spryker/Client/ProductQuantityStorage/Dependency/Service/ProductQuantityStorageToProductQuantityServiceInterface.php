<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Dependency\Service;

use Generated\Shared\Transfer\ProductQuantityTransfer;

interface ProductQuantityStorageToProductQuantityServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     * @param int $quantity
     *
     * @return int
     */
    public function getNearestQuantity(ProductQuantityTransfer $productQuantityTransfer, int $quantity): int;
}
