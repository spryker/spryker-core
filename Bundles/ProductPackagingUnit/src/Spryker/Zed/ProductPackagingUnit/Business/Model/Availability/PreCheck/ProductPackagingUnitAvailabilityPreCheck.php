<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface;
use Traversable;

abstract class ProductPackagingUnitAvailabilityPreCheck
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface $availabilityFacade
     */
    public function __construct(
        ProductPackagingUnitToAvailabilityFacadeInterface $availabilityFacade
    ) {
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertAmountPackagingUnitExpanded(ItemTransfer $itemTransfer): void
    {
        $itemTransfer
            ->requireSku()
            ->requireAmountLeadProduct()
            ->requireAmount();

        $itemTransfer->getAmountLeadProduct()
            ->requireSku();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Traversable|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isPackagingUnitLeadProductSellable(ItemTransfer $itemTransfer, Traversable $items, StoreTransfer $storeTransfer): bool
    {
        $leadProductSku = $itemTransfer->getAmountLeadProduct()->getSku();
        $leadProductQuantity = $itemTransfer->getAmount() +
            $this->getAccumaltedQuantityForLeadProduct($items, $leadProductSku, $itemTransfer);

        return $this->isProductSellableForStore(
            $leadProductSku,
            $leadProductQuantity,
            $storeTransfer
        );
    }

    /**
     * @param \Traversable|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $sku
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItem
     *
     * @return int
     */
    protected function getAccumaltedQuantityForLeadProduct(Traversable $items, string $sku, ItemTransfer $currentItem): int
    {
        $quantity = 0;
        foreach ($items as $itemTransfer) {
            if ($itemTransfer->getSku() === $currentItem->getSku()) { // Skip the current item
                continue;
            }

            if ($sku === $itemTransfer->getSku()) { // Lead product is in cart as an indivdual items
                $quantity += $itemTransfer->getQuantity();
                continue;
            }

            $productPacakgingLeadProduct = $itemTransfer->getAmountLeadProduct();
            if ($productPacakgingLeadProduct && // Other items in cart are also a pacakging unit
                // Lead product is a lead product of another item in cart
                $productPacakgingLeadProduct->getSku() === $itemTransfer->getSku()) {
                $quantity += $itemTransfer->getAmount();
            }
        }

        return $quantity;
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isProductSellableForStore(string $sku, int $quantity, StoreTransfer $storeTransfer): bool
    {
        return $this->availabilityFacade
            ->isProductSellableForStore($sku, $quantity, $storeTransfer);
    }
}
