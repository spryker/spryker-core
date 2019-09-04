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
    public function __construct(
        ProductPackagingUnitToAvailabilityFacadeInterface $availabilityFacade
    ) {
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isPackagingUnitLeadProductSellable(ItemTransfer $item, iterable $items, StoreTransfer $storeTransfer): bool
    {
        $itemLeadProductSku = $item->getAmountLeadProduct()->getProduct()->getSku();
        $accumulatedItemLeadProductQuantity = $this->getAccumulatedQuantityForLeadProduct($items, $itemLeadProductSku);

        return $this->isProductSellableForStore(
            $itemLeadProductSku,
            $accumulatedItemLeadProductQuantity,
            $storeTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $leadProductSku
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getAccumulatedQuantityForLeadProduct(iterable $items, string $leadProductSku): Decimal
    {
        $quantity = new Decimal(0);
        foreach ($items as $item) {
            if ($leadProductSku === $item->getSku()) { // Lead product is in cart as an individual item
                $quantity = $quantity->add($item->getQuantity());
                continue;
            }

            if (!$item->getAmountLeadProduct()) { // Skip remaining items without lead product
                continue;
            }

            if ($item->getAmountLeadProduct()->getProduct()->getSku() === $leadProductSku) { // Item in cart has the searched lead product
                $quantity = $quantity->add($item->getAmount());
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
