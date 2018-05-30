<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;

interface ProductPackagingUnitEntityManagerInterface
{
    /**
     * Specification:
     * - Creates new product packaging unit type.
     * - Finds product packaging unit type by ProductPackagingUnitTypeTransfer::name.
     * - Updates fields in a product packaging unit type entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function saveProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer;
}
