<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Extractor;

use ArrayObject;

class ProductMeasurementUnitItemExtractor implements ProductMeasurementUnitItemExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function extractItemsWithQuantitySalesUnit(ArrayObject $itemTransfers): array
    {
        $itemsWithProductMeasurementUnit = [];
        foreach ($itemTransfers as $index => $itemTransfer) {
            if ($itemTransfer->getQuantitySalesUnit() !== null) {
                $itemsWithProductMeasurementUnit[$index] = $itemTransfer;
            }
        }

        return $itemsWithProductMeasurementUnit;
    }
}
