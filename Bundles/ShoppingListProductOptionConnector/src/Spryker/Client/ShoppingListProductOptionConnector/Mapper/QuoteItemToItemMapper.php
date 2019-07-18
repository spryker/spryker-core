<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOptionConnector\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;

class QuoteItemToItemMapper implements QuoteItemToItemMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $quoteItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function map(ItemTransfer $quoteItemTransfer, ItemTransfer $itemTransfer): ItemTransfer
    {
        if ($this->haveSameProductOptions($quoteItemTransfer, $itemTransfer)) {
            $itemTransfer->setGroupKey($quoteItemTransfer->getGroupKey());
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $quoteItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function haveSameProductOptions(ItemTransfer $quoteItemTransfer, ItemTransfer $itemTransfer): bool
    {
        if ($quoteItemTransfer->getProductOptions()->count() !== $itemTransfer->getProductOptions()->count()) {
            return false;
        }

        $mappingFunction = function (ProductOptionTransfer $productOptionTransfer) {
            return $productOptionTransfer->getIdProductOptionValue();
        };

        $quoteItemProductOptions = array_map($mappingFunction, $itemTransfer->getProductOptions()->getArrayCopy());
        $itemProductOptions = array_map(
            $mappingFunction,
            $itemTransfer->getProductOptions()->getArrayCopy()
        );

        return empty(array_diff($quoteItemProductOptions, $itemProductOptions));
    }
}
