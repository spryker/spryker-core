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
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToCartClientInterface;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToMessengerClientInterface;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToZedRequestClientInterface;
use Spryker\Client\ShoppingList\Zed\ShoppingListStubInterface;

class CartHandler implements CartHandlerInterface
{
    /**
     * @var \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\ShoppingList\Zed\ShoppingListStubInterface
     */
    protected $shoppingListStub;

    /**
     * @var \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @var \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @param \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToCartClientInterface $cartClient
     * @param \Spryker\Client\ShoppingList\Zed\ShoppingListStubInterface $shoppingListStub
     * @param \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToZedRequestClientInterface $zedRequestClient
     * @param \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToMessengerClientInterface $messengerClient
     */
    public function __construct(
        ShoppingListToCartClientInterface $cartClient,
        ShoppingListStubInterface $shoppingListStub,
        ShoppingListToZedRequestClientInterface $zedRequestClient,
        ShoppingListToMessengerClientInterface $messengerClient
    ) {
        $this->cartClient = $cartClient;
        $this->shoppingListStub = $shoppingListStub;
        $this->zedRequestClient = $zedRequestClient;
        $this->messengerClient = $messengerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer $shoppingListAddToCartRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer
     */
    public function addItemCollectionToCart(ShoppingListAddToCartRequestCollectionTransfer $shoppingListAddToCartRequestCollectionTransfer): ShoppingListAddToCartRequestCollectionTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($this->cartClient->getQuote());
        foreach ($shoppingListAddToCartRequestCollectionTransfer->getRequests() as $shoppingListAddToCartRequestTransfer) {
            $this->assertRequestTransfer($shoppingListAddToCartRequestTransfer);
            $cartChangeTransfer->addItem(
                $this->createItemTransfer($shoppingListAddToCartRequestTransfer->getSku(), $shoppingListAddToCartRequestTransfer->getQuantity())
            );
        }

        $quoteTransfer = $this->cartClient->addValidItems($cartChangeTransfer);
        $this->addErrorMessages();
        $failedToMoveRequestCollectionTransfer = $this->getShoppingListRequestCollectionToCartDiff(
            $shoppingListAddToCartRequestCollectionTransfer,
            $quoteTransfer
        );

        return $failedToMoveRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer $ShoppingListAddToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer
     */
    protected function getShoppingListRequestCollectionToCartDiff(ShoppingListAddToCartRequestCollectionTransfer $ShoppingListAddToCartRequestCollectionTransfer, QuoteTransfer $quoteTransfer): ShoppingListAddToCartRequestCollectionTransfer
    {
        $shoppingListRequestCollectionDiff = new ShoppingListAddToCartRequestCollectionTransfer();

        $existingSkuIndex = $this->createExistingSkuIndex($quoteTransfer);

        foreach ($ShoppingListAddToCartRequestCollectionTransfer->getRequests() as $ShoppingListAddToCartRequestTransfer) {
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
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer(string $sku, int $quantity): ItemTransfer
    {
        return (new ItemTransfer())
            ->setSku($sku)
            ->setQuantity($quantity);
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
        foreach ($this->zedRequestClient->getLastResponseErrorMessages() as $messageTransfer) {
            $this->messengerClient->addErrorMessage($messageTransfer->getValue());
        }
    }
}
