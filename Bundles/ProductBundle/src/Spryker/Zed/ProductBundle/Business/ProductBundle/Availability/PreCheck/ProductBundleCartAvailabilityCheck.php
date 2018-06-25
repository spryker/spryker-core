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
    public const CART_PRE_CHECK_ITEM_AVAILABILITY_EMPTY = 'cart.pre.check.availability.failed.empty';
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
        $cartPreCheckFailedItems = new ArrayObject();
        $itemsInCart = clone $cartChangeTransfer->getQuote()->getItems();

        $storeTransfer = $cartChangeTransfer->getQuote()->getStore();
        $storeTransfer->requireName();

        $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireSku()->requireQuantity();

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
        $messageTransfers = $this->checkItemAvailability(
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
        $translationKey = $this->getItemAvailabilityTranslationKey($stock);
        return $this->createCartMessageTransfer($stock, $translationKey, $sku);
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
     * @param int $stock
     *
     * @return string
     */
    protected function getItemAvailabilityTranslationKey($stock)
    {
        $translationKey = static::CART_PRE_CHECK_ITEM_AVAILABILITY_FAILED;
        if ($stock <= 0) {
            $translationKey = static::CART_PRE_CHECK_ITEM_AVAILABILITY_EMPTY;
        }
        return $translationKey;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createBundleItemIsNotAvailableMessageTransfer(ItemTransfer $bundleItemTransfer, SpyProduct $productEntity): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::ERROR_BUNDLE_ITEM_UNAVAILABLE_TRANSLATION_KEY)
            ->setParameters([
                static::ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_BUNDLE_SKU => $bundleItemTransfer->getSku(),
                static::ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_PRODUCT_SKU => $productEntity->getSku(),
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

        $availabilityAfterBundling = $itemAvailability - $bundledItemsQuantity;

        return $availabilityAfterBundling;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    protected function checkItemAvailability(
        ArrayObject $itemsInCart,
        ItemTransfer $itemTransfer,
        StoreTransfer $storeTransfer
    ) {
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
    ) {
        $unavailableProductEntities = $this->getUnavailableItemsInBundle(
            $itemsInCart,
            $itemTransfer,
            $bundledProducts,
            $storeTransfer
        );

        return $this->getUnavailableBundleItemsMessageTransfers(
            $itemTransfer,
            $storeTransfer,
            $unavailableProductEntities
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProduct[] $unavailableProductEntities
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct[]
     */
    protected function getUnavailableBundleItemsMessageTransfers(
        ItemTransfer $itemTransfer,
        StoreTransfer $storeTransfer,
        array $unavailableProductEntities
    ): array {
        $failedBundleItems = [];

        foreach ($unavailableProductEntities as $unavailableProductEntity) {
            $availabilityEntity = $this->findAvailabilityEntityBySku($itemTransfer->getSku(), $storeTransfer);

            $failedBundleItems[] = $this->getItemUnavailableErrorMessageTransfer(
                $availabilityEntity,
                $itemTransfer,
                $unavailableProductEntity
            );
        }

        return $failedBundleItems;
    }

    /**
     * @param \Orm\Zed\Availability\Persistence\SpyAvailability $availabilityEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function getItemUnavailableErrorMessageTransfer(SpyAvailability $availabilityEntity, ItemTransfer $itemTransfer, SpyProduct $productEntity): MessageTransfer
    {
        if ($availabilityEntity->getQuantity() > 0) {
            return $this->createItemIsNotAvailableMessageTransfer($availabilityEntity->getQuantity(), $itemTransfer->getSku());
        }

        return $this->createBundleItemIsNotAvailableMessageTransfer($itemTransfer, $productEntity);
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
