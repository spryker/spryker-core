<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\Translation;

use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;

interface ProductMeasurementUnitTranslationExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function translateProductMeasurementSalesUnit(
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
    ): ProductMeasurementSalesUnitTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[] $productMeasurementSalesUnitTransfers
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function translateProductMeasurementSalesUnits(array $productMeasurementSalesUnitTransfers): array;
}
