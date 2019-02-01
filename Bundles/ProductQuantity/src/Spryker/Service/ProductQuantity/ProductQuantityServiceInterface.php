<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductQuantity;

use Generated\Shared\Transfer\ProductQuantityTransfer;

interface ProductQuantityServiceInterface
{
    /**
     * Specification:
     *  - Returns nearest valid quantity based on provided quantity and product quantity restrictions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     * @param int $quantity
     *
     * @return int
     */
    public function getNearestQuantity(ProductQuantityTransfer $productQuantityTransfer, int $quantity): int;
}
