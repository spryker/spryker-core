<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ProductPackagingUnitGroupKeyGenerator implements ProductPackagingUnitGroupKeyGeneratorInterface
{
    protected const AMOUNT_GROUP_KEY_FORMAT = '%s_amount_%s_sales_unit_id_%s';

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeGroupKeyWithAmount(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setGroupKey(
                $this->expandItemWithGroupKey($itemTransfer)
            );
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return string
     */
    protected function expandItemWithGroupKey(ItemTransfer $item): string
    {
        if (!$item->getAmountSalesUnit()) {
            return $item->getGroupKey();
        }

        return sprintf(
            static::AMOUNT_GROUP_KEY_FORMAT,
            $item->getGroupKey(),
            $item->getAmount(),
            $item->getAmountSalesUnit()->getIdProductMeasurementSalesUnit()
        );
    }
}
