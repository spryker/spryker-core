<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Extractor;

use ArrayObject;

class ProductPackagingUnitItemExtractor implements ProductPackagingUnitItemExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function extractItemsWithAmountSalesUnit(ArrayObject $itemTransfers): array
    {
        $itemsWithProductPackagingUnit = [];
        foreach ($itemTransfers as $index => $itemTransfer) {
            if ($itemTransfer->getAmountSalesUnit() !== null) {
                $itemsWithProductPackagingUnit[$index] = $itemTransfer;
            }
        }

        return $itemsWithProductPackagingUnit;
    }
}
