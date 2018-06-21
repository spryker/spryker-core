<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Product\Persistence\SpyProduct;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleCartAvailabilityCheck extends BasePreCheck implements ProductBundleCartAvailabilityCheckInterface
{
    public const CART_PRE_CHECK_ITEM_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';
    public const CART_PRE_CHECK_ITEM_AVAILABILITY_FAILED_EMPTY = 'cart.pre.check.availability.failed.empty';
    public const STOCK_TRANSLATION_PARAMETER = '%stock%';
    public const SKU_TRANSLATION_PARAMETER = '%sku%';

    protected const ERROR_BUNDLE_ITEM_UNAVAILABLE_TRANSLATION_KEY = 'product_bundle.unavailable';
    protected const ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_BUNDLE_SKU = '%bundleSku%';
    protected const ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_PRODUCT_SKU = '%productSku%';

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface
     */
    protected $availabilityQueryContainer;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductBundleToAvailabilityInterface $availabilityFacade,
        ProductBundleQueryContainerInterface $productBundleQueryContainer,
        ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer,
        ProductBundleToStoreFacadeInterface $storeFacade
    ) {
        parent::__construct($availabilityFacade, $productBundleQueryContainer, $storeFacade);

        $this->availabilityQueryContainer = $availabilityQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailability(CartChangeTransfer $cartChangeTransfer)
    {
        $itemsInCart = clone $cartChangeTransfer->getQuote()->getItems();

        $storeTransfer = $this->getStoreTransferByName(
            $cartChangeTransfer->getQuote()->getStore()
        );

        $cartPreCheckFailedItems = new ArrayObject();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireSku();
            $itemTransfer->requireQuantity();

            $itemsInCart->append($itemTransfer);

            $this->addCartPreCheckFailedItems(
                $cartChangeTransfer->getQuote(),
                $itemTransfer,
                $storeTransfer,
                $cartPreCheckFailedItems
            );
        }

        return $this->createCartPreCheckResponseTransfer($cartPreCheckFailedItems);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $cartPreCheckFailedItems
     *
     * @return void
     */
    protected function addCartPreCheckFailedItems(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        StoreTransfer $storeTransfer,
        ArrayObject $cartPreCheckFailedItems
    ): void {
        $messageTransfers = $this->getUnavailableCartItems(
            $quoteTransfer->getItems(),
            $itemTransfer,
            $storeTransfer
        );

        if (!empty($messageTransfers)) {
            foreach ($messageTransfers as $messageTransfer) {
                $cartPreCheckFailedItems[] = $messageTransfer;
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreTransferByName(StoreTransfer $storeTransfer): StoreTransfer
    {
        $storeTransfer->requireName();

        return $this->storeFacade->getStoreByName($storeTransfer->getName());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $sku
     *
     * @return int
     */
    protected function getAccumulatedItemQuantityForBundledProductsByGivenSku(ArrayObject $items, $sku)
    {
        $quantity = 0;
        foreach ($items as $itemTransfer) {
            if (!$itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }

            if ($itemTransfer->getSku() !== $sku) {
                continue;
            }
            $quantity += $itemTransfer->getQuantity();
        }

        return $quantity;
    }

    /**
     * @param int $stock
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsNotAvailableMessageTransfer($stock, $sku)
    {
        if ($stock <= 0) {
            return $this->createCartMessageTransfer(
                $stock,
                static::CART_PRE_CHECK_ITEM_AVAILABILITY_FAILED_EMPTY,
                $sku
            );
        }

        return $this->createCartMessageTransfer(
            $stock,
            static::CART_PRE_CHECK_ITEM_AVAILABILITY_FAILED,
            $sku
        );
    }

    /**
     * @param int $stock
     * @param string $translationKey
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createCartMessageTransfer($stock, $translationKey, $sku)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($translationKey);
        $messageTransfer->setParameters([
            static::SKU_TRANSLATION_PARAMETER => $sku,
            static::STOCK_TRANSLATION_PARAMETER => $stock,
        ]);

        return $messageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProduct $product
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createBundleItemIsNotAvailableMessageTransfer(ItemTransfer $bundleItemTransfer, SpyProduct $product): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::ERROR_BUNDLE_ITEM_UNAVAILABLE_TRANSLATION_KEY)
            ->setParameters([
                static::ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_BUNDLE_SKU => $bundleItemTransfer->getSku(),
                static::ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_PRODUCT_SKU => $product->getSku(),
            ]);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function findAvailabilityEntityBySku($sku, StoreTransfer $storeTransfer)
    {
        return $this->availabilityQueryContainer
            ->querySpyAvailabilityBySku($sku, $storeTransfer->getIdStore())
            ->findOne();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $messages
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function createCartPreCheckResponseTransfer(ArrayObject $messages)
    {
        return (new CartPreCheckResponseTransfer())
            ->setIsSuccess(count($messages) == 0)
            ->setMessages($messages);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    protected function calculateRegularItemAvailability(
        ItemTransfer $itemTransfer,
        ArrayObject $itemsInCart,
        StoreTransfer $storeTransfer
    ) {
        $itemAvailability = $this->availabilityFacade->calculateStockForProductWithStore(
            $itemTransfer->getSku(),
            $storeTransfer
        );

        $bundledItemsQuantity = $this->getAccumulatedItemQuantityForBundledProductsByGivenSku(
            $itemsInCart,
            $itemTransfer->getSku()
        );

        return $itemAvailability - $bundledItemsQuantity;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    protected function getUnavailableCartItems(
        ArrayObject $itemsInCart,
        ItemTransfer $itemTransfer,
        StoreTransfer $storeTransfer
    ): array {
        $bundledProducts = $this->findBundledProducts($itemTransfer->getSku());

        if (count($bundledProducts) > 0) {
            return $this->checkBundleAvailability($itemsInCart, $bundledProducts, $itemTransfer, $storeTransfer);
        }

        $regularItemAvailability = $this->checkRegularItemAvailability($itemsInCart, $itemTransfer, $storeTransfer);

        if ($regularItemAvailability !== null) {
            return [
                $regularItemAvailability,
            ];
        }

        return [];
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection $bundledProducts
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    protected function checkBundleAvailability(
        ArrayObject $itemsInCart,
        ObjectCollection $bundledProducts,
        ItemTransfer $itemTransfer,
        StoreTransfer $storeTransfer
    ): array {
        $unavailableItems = $this->getUnavailableItemsInBundle(
            $itemsInCart,
            $itemTransfer,
            $bundledProducts,
            $storeTransfer
        );

        if (empty($unavailableItems)) {
            return [];
        }

        $messageTransfers = new ArrayObject();

        foreach ($unavailableItems as $unavailableItem) {
            $this->getUnavailableBundleItemsMessageTransfers(
                $itemTransfer,
                $storeTransfer,
                $unavailableItem,
                $messageTransfers
            );
        }

        return $messageTransfers->getArrayCopy();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProduct $unavailableItem
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $failedBundleItems
     *
     * @return void
     */
    protected function getUnavailableBundleItemsMessageTransfers(
        ItemTransfer $itemTransfer,
        StoreTransfer $storeTransfer,
        SpyProduct $unavailableItem,
        ArrayObject $failedBundleItems
    ): void {
        $availabilityEntity = $this->findAvailabilityEntityBySku($itemTransfer->getSku(), $storeTransfer);

        $failedBundleItems[] = $this->getItemUnavailableErrorMessageTransfer(
            $availabilityEntity,
            $itemTransfer,
            $unavailableItem
        );
    }

    /**
     * @param \Orm\Zed\Availability\Persistence\SpyAvailability $availability
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProduct $product
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function getItemUnavailableErrorMessageTransfer(SpyAvailability $availability, ItemTransfer $itemTransfer, SpyProduct $product): MessageTransfer
    {
        if ($availability->getQuantity() > 0) {
            return $this->createItemIsNotAvailableMessageTransfer($availability->getQuantity(), $itemTransfer->getSku());
        }

        return $this->createBundleItemIsNotAvailableMessageTransfer($itemTransfer, $product);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function checkRegularItemAvailability($itemsInCart, ItemTransfer $itemTransfer, StoreTransfer $storeTransfer)
    {
        if ($this->checkIfItemIsSellable($itemsInCart, $itemTransfer->getSku(), $storeTransfer, $itemTransfer->getQuantity())) {
            return null;
        }

        $availability = $this->calculateRegularItemAvailability($itemTransfer, $itemsInCart, $storeTransfer);

        return $this->createItemIsNotAvailableMessageTransfer($availability, $itemTransfer->getSku());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \Propel\Runtime\Collection\ObjectCollection $bundledProducts
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct[]
     */
    protected function getUnavailableItemsInBundle(
        ArrayObject $itemsInCart,
        ItemTransfer $bundleItemTransfer,
        ObjectCollection $bundledProducts,
        StoreTransfer $storeTransfer
    ): array {
        $unavailableItems = [];

        foreach ($bundledProducts as $productBundleEntity) {
            $bundledProductConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

            $sku = $bundledProductConcreteEntity->getSku();
            $totalBundledItemQuantity = $productBundleEntity->getQuantity() * $bundleItemTransfer->getQuantity();

            if (!$this->checkIfItemIsSellable($itemsInCart, $sku, $storeTransfer, $totalBundledItemQuantity)) {
                $unavailableItems[] = $bundledProductConcreteEntity;
            }
        }

        return $unavailableItems;
    }
}
