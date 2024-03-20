<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListAddToCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToCartClientInterface;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToMessengerClientInterface;
use Spryker\Client\ShoppingList\Zed\ShoppingListStubInterface;

class CartHandler implements CartHandlerInterface
{
    /**
     * @uses Spryker\Client\SharedCart\Plugin\ProductSeparatePersistentCartChangeExpanderPlugin::PARAM_SEPARATE_PRODUCT
     *
     * @var string
     */
    protected const PARAM_SEPARATE_PRODUCT = 'separate_product';

    /**
     * @var \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\ShoppingList\Zed\ShoppingListStubInterface
     */
    protected $shoppingListStub;

    /**
     * @var \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @var array<\Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemToItemMapperPluginInterface>
     */
    protected $shoppingListItemToItemMapperPlugins;

    /**
     * @var array<\Spryker\Client\ShoppingListExtension\Dependency\Plugin\QuoteItemToItemMapperPluginInterface>
     */
    protected $quoteItemToItemMapperPlugins;

    /**
     * @param \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToCartClientInterface $cartClient
     * @param \Spryker\Client\ShoppingList\Zed\ShoppingListStubInterface $shoppingListStub
     * @param \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToMessengerClientInterface $messengerClient
     * @param array<\Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemToItemMapperPluginInterface> $shoppingListItemToItemMapperPlugins
     * @param array<\Spryker\Client\ShoppingListExtension\Dependency\Plugin\QuoteItemToItemMapperPluginInterface> $quoteItemToItemMapperPlugins
     */
    public function __construct(
        ShoppingListToCartClientInterface $cartClient,
        ShoppingListStubInterface $shoppingListStub,
        ShoppingListToMessengerClientInterface $messengerClient,
        array $shoppingListItemToItemMapperPlugins,
        array $quoteItemToItemMapperPlugins
    ) {
        $this->cartClient = $cartClient;
        $this->shoppingListStub = $shoppingListStub;
        $this->messengerClient = $messengerClient;
        $this->shoppingListItemToItemMapperPlugins = $shoppingListItemToItemMapperPlugins;
        $this->quoteItemToItemMapperPlugins = $quoteItemToItemMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer $shoppingListAddToCartRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer
     */
    public function addItemCollectionToCart(
        ShoppingListAddToCartRequestCollectionTransfer $shoppingListAddToCartRequestCollectionTransfer
    ): ShoppingListAddToCartRequestCollectionTransfer {
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($this->cartClient->getQuote());
        foreach ($shoppingListAddToCartRequestCollectionTransfer->getRequests() as $shoppingListAddToCartRequestTransfer) {
            $this->assertRequestTransfer($shoppingListAddToCartRequestTransfer);
            $cartChangeTransfer->addItem(
                $this->createItemTransfer($shoppingListAddToCartRequestTransfer),
            );
        }

        $quoteTransfer = $this->cartClient->addValidItems($cartChangeTransfer, [static::PARAM_SEPARATE_PRODUCT => true]);
        $this->addErrorMessages();
        $failedToMoveRequestCollectionTransfer = $this->getShoppingListRequestCollectionToCartDiff(
            $shoppingListAddToCartRequestCollectionTransfer,
            $quoteTransfer,
        );

        return $failedToMoveRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer $shoppingListAddToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer
     */
    protected function getShoppingListRequestCollectionToCartDiff(
        ShoppingListAddToCartRequestCollectionTransfer $shoppingListAddToCartRequestCollectionTransfer,
        QuoteTransfer $quoteTransfer
    ): ShoppingListAddToCartRequestCollectionTransfer {
        $shoppingListRequestCollectionDiff = new ShoppingListAddToCartRequestCollectionTransfer();

        $existingSkuIndex = $this->createExistingSkuIndex($quoteTransfer);

        foreach ($shoppingListAddToCartRequestCollectionTransfer->getRequests() as $ShoppingListAddToCartRequestTransfer) {
            if (isset($existingSkuIndex[$ShoppingListAddToCartRequestTransfer->getSku()])) {
                continue;
            }

            $shoppingListRequestCollectionDiff->addRequest($ShoppingListAddToCartRequestTransfer);
        }

        return $shoppingListRequestCollectionDiff;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListAddToCartRequestTransfer $shoppingListAddToCartRequestTransfer
     *
     * @return void
     */
    protected function assertRequestTransfer(ShoppingListAddToCartRequestTransfer $shoppingListAddToCartRequestTransfer): void
    {
        $shoppingListAddToCartRequestTransfer->requireSku();
        $shoppingListAddToCartRequestTransfer->requireQuantity();
        $shoppingListAddToCartRequestTransfer->requireShoppingListItem();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListAddToCartRequestTransfer $shoppingListAddToCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer(ShoppingListAddToCartRequestTransfer $shoppingListAddToCartRequestTransfer): ItemTransfer
    {
        $itemTransfer = (new ItemTransfer())
            ->setSku($shoppingListAddToCartRequestTransfer->getSku())
            ->setQuantity($shoppingListAddToCartRequestTransfer->getQuantity());
        $itemTransfer = $this->mapShoppingListItemToItem($shoppingListAddToCartRequestTransfer->getShoppingListItem(), $itemTransfer);
        $itemTransfer = $this->mapQuoteItemToItem($this->findItemInQuote($itemTransfer), $itemTransfer);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function mapShoppingListItemToItem(ShoppingListItemTransfer $shoppingListItemTransfer, ItemTransfer $itemTransfer): ItemTransfer
    {
        foreach ($this->shoppingListItemToItemMapperPlugins as $shoppingListItemToItemMapperPlugin) {
            $itemTransfer = $shoppingListItemToItemMapperPlugin->map($shoppingListItemTransfer, $itemTransfer);
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer|null $quoteItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function mapQuoteItemToItem(?ItemTransfer $quoteItemTransfer, ItemTransfer $itemTransfer): ItemTransfer
    {
        if (!$quoteItemTransfer) {
            return $itemTransfer;
        }

        foreach ($this->quoteItemToItemMapperPlugins as $quoteItemToItemMapperPlugin) {
            $itemTransfer = $quoteItemToItemMapperPlugin->map($quoteItemTransfer, $itemTransfer);
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItemInQuote(ItemTransfer $itemTransfer): ?ItemTransfer
    {
        $quoteTransfer = $this->cartClient->getQuote();

        return $this->cartClient->findQuoteItem($quoteTransfer, $itemTransfer->getSku());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function createExistingSkuIndex(QuoteTransfer $quoteTransfer): array
    {
        $skuIndex = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $skuIndex[$itemTransfer->getSku()] = true;
        }

        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            $skuIndex[$itemTransfer->getSku()] = true;
        }

        return $skuIndex;
    }

    /**
     * @return void
     */
    protected function addErrorMessages(): void
    {
        foreach ($this->shoppingListStub->getResponsesErrorMessages() as $messageTransfer) {
            $this->messengerClient->addErrorMessage($messageTransfer->getValue());
        }
    }
}
