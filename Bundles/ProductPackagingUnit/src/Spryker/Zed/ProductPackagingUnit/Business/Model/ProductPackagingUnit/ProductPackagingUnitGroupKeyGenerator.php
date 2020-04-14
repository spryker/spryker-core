<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit;

use Generated\Shared\Transfer\ItemTransfer;

class ProductPackagingUnitGroupKeyGenerator implements ProductPackagingUnitGroupKeyGeneratorInterface
{
    protected const AMOUNT_GROUP_KEY_FORMAT = '%s_amount_%s_sales_unit_id_%s';
    protected const DIVISION_SCALE = 10;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function getItemWithGroupKey(ItemTransfer $itemTransfer): string
    {
        if (!$itemTransfer->getAmountSalesUnit() || !$itemTransfer->getAmount()) {
            return $itemTransfer->getGroupKey();
        }

        $amountPerQuantity = $itemTransfer->getAmount()->divide($itemTransfer->getQuantity(), static::DIVISION_SCALE);

        return sprintf(
            static::AMOUNT_GROUP_KEY_FORMAT,
            $itemTransfer->getGroupKey(),
            $amountPerQuantity->trim()->toString(),
            $itemTransfer->getAmountSalesUnit()->getIdProductMeasurementSalesUnit()
        );
    }
}
