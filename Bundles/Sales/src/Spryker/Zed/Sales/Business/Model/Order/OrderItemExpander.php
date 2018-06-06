<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;

class OrderItemExpander implements OrderItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItem(ItemTransfer $itemTransfer): ArrayObject
    {
        $expandedItems = new ArrayObject();

        $quantity = $itemTransfer->getQuantity();
        for ($i = 1; $quantity >= $i; $i++) {
            $expandedItemTransfer = new ItemTransfer();
            $expandedItemTransfer->fromArray($itemTransfer->toArray(), true);
            $expandedItemTransfer->setQuantity(1);

            $expandedProductOptions = new ArrayObject();
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $expandedProductOptions->append($this->copyProductOptionTransfer($productOptionTransfer));
            }

            $expandedItemTransfer->setProductOptions($expandedProductOptions);
            $expandedItems->append($expandedItemTransfer);
        }

        return $expandedItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function copyProductOptionTransfer(ProductOptionTransfer $productOptionTransfer): ProductOptionTransfer
    {
        $expandedProductOptionTransfer = new ProductOptionTransfer();
        $expandedProductOptionTransfer->fromArray($productOptionTransfer->toArray(), true);
        $expandedProductOptionTransfer->setQuantity(1);
        $expandedProductOptionTransfer->setIdProductOptionValue(null);

        return $expandedProductOptionTransfer;
    }
}
