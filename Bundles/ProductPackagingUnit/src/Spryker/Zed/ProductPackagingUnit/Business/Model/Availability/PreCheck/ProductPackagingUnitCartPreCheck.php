<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface;
use Traversable;

class ProductPackagingUnitCartPreCheck implements ProductPackagingUnitCartPreCheckInterface
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
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailability(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $messages = new ArrayObject();
        $this->assertQuote($cartChangeTransfer);
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmountLeadProduct()) {
                continue;
            }

            $this->assertQuantityPackagingUnitExpanded($itemTransfer);

            if (!$this->isPackagingUnitSellable($itemTransfer, $cartChangeTransfer->getQuote()->getStore())) {
                $messages->append('Create error message'); //TODO
                continue;
            }

            if ($itemTransfer->getAmountLeadProduct() && $itemTransfer->getAmount() > 0) {
                if (!$this->isPackagingUnitLeadProductSellable(
                    $itemTransfer,
                    $cartChangeTransfer->getItems(),
                    $cartChangeTransfer->getQuote()->getStore()
                )) {
                    $messages->append('Create error message'); //TODO
                }
            }
        }

        return $this->createCartPreCheckResponseTransfer($messages);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return void
     */
    protected function assertQuote(CartChangeTransfer $cartChangeTransfer)
    {
        $cartChangeTransfer->requireQuote();

        $cartChangeTransfer->getQuote()->requireStore();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertQuantityPackagingUnitExpanded(ItemTransfer $itemTransfer): void
    {
        $itemTransfer->requireSku();

        $itemTransfer->requireAmountLeadProduct()
            ->requireAmount();

        $itemTransfer->getAmountLeadProduct()
            ->requireSkuProduct();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isPackagingUnitSellable(ItemTransfer $itemTransfer, StoreTransfer $storeTransfer): bool
    {
        return $this->isProductSellableForStore($itemTransfer->getSku(), $itemTransfer->getQuantity(), $storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isPackagingUnitLeadProductSellable(ItemTransfer $itemTransfer, Traversable $items, StoreTransfer $storeTransfer): bool
    {
        $leadProductSku = $itemTransfer->getAmountLeadProduct()->getSkuProduct();
        $leadProductQuantity = $itemTransfer->getAmount() +
            $this->getAccumaltedQuantityForLeadProduct($items, $leadProductSku, $itemTransfer);

        return $this->isProductSellableForStore(
            $leadProductSku,
            $leadProductQuantity,
            $storeTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
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
                $productPacakgingLeadProduct->getSkuProduct() === $itemTransfer->getSku()) {
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
     * @return int
     */
    protected function isProductSellableForStore(string $sku, int $quantity, StoreTransfer $storeTransfer): int
    {
        return $this->availabilityFacade
            ->isProductSellableForStore($sku, $quantity, $storeTransfer);
    }

    /**
     * @param \ArrayObject $messages
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function createCartPreCheckResponseTransfer(ArrayObject $messages)
    {
        return (new CartPreCheckResponseTransfer())
            ->setIsSuccess($messages->count() === 0)
            ->setMessages($messages);
    }
}
