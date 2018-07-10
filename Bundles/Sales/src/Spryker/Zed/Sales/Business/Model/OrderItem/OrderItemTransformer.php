<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\OrderItem;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationInterface;

class OrderItemTransformer implements OrderItemTransformerInterface
{
    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationInterface
     */
    protected $calculationFacade;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationInterface $calculationFacade
     */
    public function __construct(SalesToCalculationInterface $calculationFacade)
    {
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function transformSplittableItem(ItemTransfer $itemTransfer): ItemCollectionTransfer
    {
        $transformedItemsCollection = new ItemCollectionTransfer();

        $quantity = $itemTransfer->getQuantity();
        for ($i = 1; $quantity >= $i; $i++) {
            $transformedItemTransfer = new ItemTransfer();
            $transformedItemTransfer->fromArray($itemTransfer->toArray(), true);
            $transformedItemTransfer->setQuantity(1);

            $transformedProductOptions = new ArrayObject();
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $transformedProductOptions->append($this->copyProductOptionTransfer($productOptionTransfer));
            }

            $transformedItemTransfer->setProductOptions($transformedProductOptions);
            $transformedItemsCollection->addItem($transformedItemTransfer);
        }

        $transformedItemsCollection = $this->calculationFacade->removeItemTransferSumPrices($transformedItemsCollection);

        return $transformedItemsCollection;
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
            ->setQuantity(1)
            ->setIdProductOptionValue(null);

        return $transformedProductOptionTransfer;
    }
}
