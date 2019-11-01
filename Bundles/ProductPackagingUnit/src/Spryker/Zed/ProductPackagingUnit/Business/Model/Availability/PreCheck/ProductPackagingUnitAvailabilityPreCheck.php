<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface;

abstract class ProductPackagingUnitAvailabilityPreCheck
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface $availabilityFacade
     */
    public function __construct(ProductPackagingUnitToAvailabilityFacadeInterface $availabilityFacade)
    {
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isPackagingUnitSellable(ItemTransfer $itemTransfer, StoreTransfer $storeTransfer): bool
    {
        if ($itemTransfer->getAmountLeadProduct()->getSku() === $itemTransfer->getSku()) {
            // self-lead PU
            return true;
        }

        return $this->isProductSellableForStore(
            $itemTransfer->getSku(),
            new Decimal($itemTransfer->getQuantity()),
            $storeTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function findProductConcreteAvailability(ItemTransfer $itemTransfer, StoreTransfer $storeTransfer): Decimal
    {
        $productConcreteAvailabilityTransfer = $this->availabilityFacade
            ->findOrCreateProductConcreteAvailabilityBySkuForStore($itemTransfer->getSku(), $storeTransfer);

        if ($productConcreteAvailabilityTransfer !== null) {
            return $productConcreteAvailabilityTransfer->getAvailability();
        }

        return new Decimal(0);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isPackagingUnitLeadProductSellable(ItemTransfer $itemTransfer, iterable $items, StoreTransfer $storeTransfer): bool
    {
        $itemLeadProductSku = $itemTransfer->getAmountLeadProduct()->getSku();
        $accumulatedItemLeadProductQuantity = $this->getAccumulatedQuantityForLeadProduct($items, $itemLeadProductSku);

        return $this->isProductSellableForStore(
            $itemLeadProductSku,
            $accumulatedItemLeadProductQuantity,
            $storeTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param string $leadProductSku
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getAccumulatedQuantityForLeadProduct(iterable $itemTransfers, string $leadProductSku): Decimal
    {
        $quantity = new Decimal(0);
        foreach ($itemTransfers as $itemTransfer) {
            if ($leadProductSku === $itemTransfer->getSku() && $leadProductSku !== $itemTransfer->getAmountLeadProduct()->getSku()) {
                // Lead product is in cart as an individual item (but not self-lead)
                $quantity = $quantity->add($itemTransfer->getQuantity());
                continue;
            }

            if (!$itemTransfer->getAmountLeadProduct()) {
                // Skip remaining items without lead product
                continue;
            }

            if ($itemTransfer->getAmountLeadProduct()->getSku() === $leadProductSku) {
                // Item in cart has the matched lead product
                $quantity = $quantity->add($itemTransfer->getAmount());
            }
        }

        return $quantity;
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isProductSellableForStore(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): bool
    {
        return $this->availabilityFacade->isProductSellableForStore($sku, $quantity, $storeTransfer);
    }
}
