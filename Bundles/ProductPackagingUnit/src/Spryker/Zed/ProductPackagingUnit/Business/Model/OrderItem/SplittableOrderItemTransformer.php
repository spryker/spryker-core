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
use Spryker\Service\ProductPackagingUnit\ProductPackagingUnitServiceInterface;

class SplittableOrderItemTransformer implements SplittableOrderItemTransformerInterface
{
    /**
     * @var \Spryker\Service\ProductPackagingUnit\ProductPackagingUnitServiceInterface
     */
    protected $service;

    /**
     * @param \Spryker\Service\ProductPackagingUnit\ProductPackagingUnitServiceInterface $service
     */
    public function __construct(ProductPackagingUnitServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @see \Spryker\Zed\Sales\Business\Model\OrderItem\OrderItemTransformer
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function transformItem(ItemTransfer $itemTransfer): ItemCollectionTransfer
    {
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
        $transformedItemTransfer->setQuantity(1.0);
        $amountPerQuantity = $itemTransfer->getAmount() / $itemTransfer->getQuantity();
        $amountPerQuantity = $this->service->round($amountPerQuantity);
        $transformedItemTransfer->setAmount((int)$amountPerQuantity);
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
            ->setQuantity(1.0);

        return $transformedProductOptionTransfer;
    }
}
