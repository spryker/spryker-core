<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;

interface ProductPackagingUnitFacadeInterface
{
    /**
     * Specification:
     * - Add infrastructural packaging unit types list to storage.
     *
     * @api
     *
     * @return void
     */
    public function installProductPackagingUnitTypes(): void;

    /**
     * Specification:
     *  - Retrieve a product packaging unit type by ProductPackagingUnitTypeTransfer::name in the transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function getProductPackagingUnitTypeByName(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer;
}
