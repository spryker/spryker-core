<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Business\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\SellableItemRequestTransfer;
use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface;

class CheckCartAvailability implements CheckCartAvailabilityInterface
{
    public const CART_PRE_CHECK_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';
    public const CART_PRE_CHECK_AVAILABILITY_EMPTY = 'cart.pre.check.availability.failed.empty';
    public const STOCK_TRANSLATION_PARAMETER = '%stock%';
    public const SKU_TRANSLATION_PARAMETER = '%sku%';

    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface[]
     */
    protected $cartItemQuantityCounterStrategyPlugins;

    /**
     * @param \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface[] $cartItemQuantityCounterStrategyPlugins
     */
    public function __construct(
        AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade,
        array $cartItemQuantityCounterStrategyPlugins
    ) {
        $this->availabilityFacade = $availabilityFacade;
        $this->cartItemQuantityCounterStrategyPlugins = $cartItemQuantityCounterStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailabilityBatch(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $sellableItemsRequestTransfer = $this->createSellableItemsRequestTransfer($cartChangeTransfer);
        $sellableItemsResponseTransfer = $this->availabilityFacade->areProductsSellableForStore($sellableItemsRequestTransfer);

        return $this->createCartPreCheckResponseTransfer($sellableItemsResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsRequestTransfer
     */
    protected function createSellableItemsRequestTransfer(CartChangeTransfer $cartChangeTransfer): SellableItemsRequestTransfer
    {
        $storeTransfer = $this->getStoreTransfer($cartChangeTransfer);
        $itemsInCart = clone $cartChangeTransfer->getQuote()->getItems();
        $sellableItemsRequestTransfer = new SellableItemsRequestTransfer();
        $sellableItemsRequestTransfer->setStore($storeTransfer);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getAmount() !== null) {
                continue;
            }

            $sellableItemRequestTransfer = new SellableItemRequestTransfer();
            $sellableItemRequestTransfer->setQuantity($this->calculateTotalItemQuantity($itemsInCart, $itemTransfer));
            $sellableItemRequestTransfer->setProductAvailabilityCriteria(
                (new ProductAvailabilityCriteriaTransfer())
                    ->fromArray($itemTransfer->toArray(), true)
            );
            $itemsInCart->append($itemTransfer);
            $sellableItemRequestTransfer->setSku($itemTransfer->getSku());
            $sellableItemsRequestTransfer->addSellableItemRequest($sellableItemRequestTransfer);
        }

        return $sellableItemsRequestTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateTotalItemQuantity(ArrayObject $itemsInCart, ItemTransfer $itemTransfer): Decimal
    {
        $currentItemQuantity = $this->calculateCurrentCartItemQuantity($itemsInCart, $itemTransfer);
        $currentItemQuantity += $itemTransfer->getQuantity();

        return new Decimal($currentItemQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsResponseTransfer $SellableItemsResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function createCartPreCheckResponseTransfer(
        SellableItemsResponseTransfer $SellableItemsResponseTransfer
    ): CartPreCheckResponseTransfer {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(true);
        $messages = new ArrayObject();
        foreach ($SellableItemsResponseTransfer->getSellableItemResponses() as $sellableItemResponseTransfer) {
            if (!$sellableItemResponseTransfer->getIsSellable()) {
                $cartPreCheckResponseTransfer->setIsSuccess(false);
                $messages[] = $this->createItemIsNotAvailableMessageTransfer(
                    $sellableItemResponseTransfer->getAvailableQuantity(),
                    $sellableItemResponseTransfer->getSku()
                );
            }
        }
        $cartPreCheckResponseTransfer->setMessages($messages);

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailability(CartChangeTransfer $cartChangeTransfer)
    {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(true);

        $storeTransfer = $this->getStoreTransfer($cartChangeTransfer);
        $itemsInCart = clone $cartChangeTransfer->getQuote()->getItems();

        $messages = new ArrayObject();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getAmount() !== null) {
                continue;
            }

            $currentItemQuantity = $this->calculateCurrentCartItemQuantity($itemsInCart, $itemTransfer);

            $currentItemQuantity += $itemTransfer->getQuantity();

            $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
                ->fromArray($itemTransfer->toArray(), true);

            $isSellable = $this->availabilityFacade->isProductSellableForStore(
                $itemTransfer->getSku(),
                new Decimal($currentItemQuantity),
                $storeTransfer,
                $productAvailabilityCriteriaTransfer
            );

            if (!$isSellable) {
                $availability = $this->findProductConcreteAvailability($itemTransfer, $storeTransfer, $productAvailabilityCriteriaTransfer);
                $cartPreCheckResponseTransfer->setIsSuccess(false);
                $messages[] = $this->createItemIsNotAvailableMessageTransfer($availability, $itemTransfer->getSku());
            }

            $itemsInCart->append($itemTransfer);
        }

        $cartPreCheckResponseTransfer->setMessages($messages);

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function calculateCurrentCartItemQuantity(ArrayObject $itemsInCart, ItemTransfer $itemTransfer): int
    {
        foreach ($this->cartItemQuantityCounterStrategyPlugins as $cartItemQuantityCounterStrategyPlugin) {
            if ($cartItemQuantityCounterStrategyPlugin->isApplicable($itemsInCart, $itemTransfer)) {
                $cartItemQuantityTransfer = $cartItemQuantityCounterStrategyPlugin->countCartItemQuantity(
                    $itemsInCart,
                    $itemTransfer
                );

                return $cartItemQuantityTransfer->getQuantity();
            }
        }

        return $this->calculateCurrentCartQuantityForGivenSku(
            $itemsInCart,
            $itemTransfer->getSku()
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $sku
     *
     * @return int
     */
    protected function calculateCurrentCartQuantityForGivenSku(ArrayObject $items, $sku)
    {
        $quantity = 0;
        foreach ($items as $itemTransfer) {
            if ($itemTransfer->getSku() !== $sku) {
                continue;
            }
            $quantity += $itemTransfer->getQuantity();
        }

        return $quantity;
    }

    /**
     * @param \Spryker\DecimalObject\Decimal $availability
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsNotAvailableMessageTransfer(Decimal $availability, string $sku): MessageTransfer
    {
        $translationKey = $this->getTranslationKey($availability);

        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($translationKey);
        $messageTransfer->setParameters([
            static::STOCK_TRANSLATION_PARAMETER => $availability->trim()->toString(),
            static::SKU_TRANSLATION_PARAMETER => $sku,
        ]);

        return $messageTransfer;
    }

    /**
     * @param \Spryker\DecimalObject\Decimal $availability
     *
     * @return string
     */
    protected function getTranslationKey(Decimal $availability): string
    {
        $translationKey = static::CART_PRE_CHECK_AVAILABILITY_FAILED;
        if ($availability->lessThanOrEquals(0)) {
            $translationKey = static::CART_PRE_CHECK_AVAILABILITY_EMPTY;
        }

        return $translationKey;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreTransfer(CartChangeTransfer $cartChangeTransfer): StoreTransfer
    {
        $cartChangeTransfer
            ->getQuote()
                ->requireStore();

        return $cartChangeTransfer->getQuote()->getStore();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function findProductConcreteAvailability(
        ItemTransfer $itemTransfer,
        StoreTransfer $storeTransfer,
        ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
    ): Decimal {
        $productConcreteAvailabilityTransfer = $this->availabilityFacade
            ->findOrCreateProductConcreteAvailabilityBySkuForStore($itemTransfer->getSku(), $storeTransfer, $productAvailabilityCriteriaTransfer);

        if ($productConcreteAvailabilityTransfer !== null) {
            return $productConcreteAvailabilityTransfer->getAvailability();
        }

        return new Decimal(0);
    }
}
