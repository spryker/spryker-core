<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

interface ProductMeasurementUnitEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
     *
     * @return void
     */
    public function saveProductMeasurementUnit(
        ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
    ): void;
}
