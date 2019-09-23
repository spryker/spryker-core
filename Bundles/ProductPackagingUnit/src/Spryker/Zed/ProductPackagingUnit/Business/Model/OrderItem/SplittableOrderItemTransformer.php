<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;

class SplittableOrderItemTransformer implements SplittableOrderItemTransformerInterface
{
    protected const DIVISION_SCALE = 10;

    /**
     * @see \Spryker\Zed\Sales\Business\Model\OrderItem\OrderItemTransformer
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function transformItem(ItemTransfer $itemTransfer): ItemCollectionTransfer
    {
        $itemTransfer->requireAmount();

        $transformedItemsCollection = new ItemCollectionTransfer();
        $quantity = $itemTransfer->getQuantity();

        for ($i = 1; $quantity >= $i; $i++) {
            $this->transformItemTransferWithProductOptions($itemTransfer, $transformedItemsCollection);
        }

        return $transformedItemsCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemCollectionTransfer $transformedItemsCollection
     *
     * @return void
     */
    protected function transformItemTransferWithProductOptions(ItemTransfer $itemTransfer, ItemCollectionTransfer $transformedItemsCollection): void
    {
        $transformedItemTransfer = new ItemTransfer();
        $transformedItemTransfer->fromArray($itemTransfer->toArray(), true);
        $transformedItemTransfer->setQuantity(1);
        $amountPerQuantity = $itemTransfer->getAmount()->divide($itemTransfer->getQuantity(), static::DIVISION_SCALE);
        $transformedItemTransfer->setAmount($amountPerQuantity);
        $transformedProductOptions = new ArrayObject();

        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $transformedProductOptions->append($this->copyProductOptionTransfer($productOptionTransfer));
        }

        $transformedItemTransfer->setProductOptions($transformedProductOptions);
        $transformedItemsCollection->addItem($transformedItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function copyProductOptionTransfer(ProductOptionTransfer $productOptionTransfer): ProductOptionTransfer
    {
        $transformedProductOptionTransfer = new ProductOptionTransfer();

        $transformedProductOptionTransfer->fromArray($productOptionTransfer->toArray(), true);
        $transformedProductOptionTransfer
            ->setQuantity(1);

        return $transformedProductOptionTransfer;
    }
}
