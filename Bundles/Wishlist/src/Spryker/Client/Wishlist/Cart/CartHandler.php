<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestTransfer;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToCartInterface;
use Spryker\Client\Wishlist\WishlistClientInterface;

class CartHandler implements CartHandlerInterface
{
    /**
     * @var \Spryker\Client\Wishlist\Dependency\Client\WishlistToCartInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\Wishlist\WishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @var \Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistPostMoveToCartCollectionExpanderPluginInterface[]
     */
    protected $wishlistPostMoveToCartCollectionExpanderPlugins;

    /**
     * @var \Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistCollectionToRemoveExpanderPluginInterface[]
     */
    protected $wishlistCollectionToRemoveExpanderPlugins;

    /**
     * @param \Spryker\Client\Wishlist\Dependency\Client\WishlistToCartInterface $cartClient
     * @param \Spryker\Client\Wishlist\WishlistClientInterface $wishlistClient
     * @param \Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistPostMoveToCartCollectionExpanderPluginInterface[] $wishlistPostMoveToCartCollectionExpanderPlugins
     * @param \Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistCollectionToRemoveExpanderPluginInterface[] $wishlistCollectionToRemoveExpanderPlugins
     */
    public function __construct(
        WishlistToCartInterface $cartClient,
        WishlistClientInterface $wishlistClient,
        array $wishlistPostMoveToCartCollectionExpanderPlugins,
        array $wishlistCollectionToRemoveExpanderPlugins
    ) {
        $this->cartClient = $cartClient;
        $this->wishlistClient = $wishlistClient;
        $this->wishlistPostMoveToCartCollectionExpanderPlugins = $wishlistPostMoveToCartCollectionExpanderPlugins;
        $this->wishlistCollectionToRemoveExpanderPlugins = $wishlistCollectionToRemoveExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $requestCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    protected function getWishlistRequestCollectionToCartDiff(
        WishlistMoveToCartRequestCollectionTransfer $requestCollectionTransfer,
        QuoteTransfer $quoteTransfer
    ) {
        $wishlistRequestCollectionDiff = new WishlistMoveToCartRequestCollectionTransfer();

        $existingSkuIndex = $this->createExistingSkuIndex($quoteTransfer);

        foreach ($requestCollectionTransfer->getRequests() as $wishlistRequestTransfer) {
            if (isset($existingSkuIndex[$wishlistRequestTransfer->getSku()])) {
                continue;
            }

            $wishlistRequestCollectionDiff->addRequest($wishlistRequestTransfer);
        }

        $wishlistRequestCollectionDiff = $this->executeWishlistPostMoveToCartCollectionExpanderPlugins(
            $wishlistRequestCollectionDiff,
            $quoteTransfer,
            $requestCollectionTransfer
        );

        return $wishlistRequestCollectionDiff;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $requestedCollection
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $failedCollection
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer
     */
    protected function getWishlistCollectionToRemove(
        WishlistMoveToCartRequestCollectionTransfer $requestedCollection,
        WishlistMoveToCartRequestCollectionTransfer $failedCollection
    ) {
        $failedSkus = [];
        $successfulRequestCollection = new WishlistItemCollectionTransfer();

        foreach ($failedCollection->getRequests() as $requestTransfer) {
            $failedSkus[] = $requestTransfer->getSku();
        }

        foreach ($requestedCollection->getRequests() as $requestTransfer) {
            if (in_array($requestTransfer->getSku(), $failedSkus)) {
                continue;
            }

            $successfulRequestCollection->addItem($requestTransfer->getWishlistItem());
        }

        $successfulRequestCollection = $this->executeWishlistCollectionToRemoveExpanderPlugins(
            $requestedCollection,
            $failedCollection,
            $successfulRequestCollection
        );

        return $successfulRequestCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    public function moveCollectionToCart(WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer)
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($this->cartClient->getQuote());
        foreach ($wishlistMoveToCartRequestCollectionTransfer->getRequests() as $wishlistMoveToCartRequestTransfer) {
            $this->assertRequestTransfer($wishlistMoveToCartRequestTransfer);
            $itemTransfer = $this->createItemTransfer($wishlistMoveToCartRequestTransfer->getSku());
            $itemTransfer->fromArray($wishlistMoveToCartRequestTransfer->toArray(), true);
            $cartChangeTransfer->addItem($itemTransfer);
        }
        $quoteTransfer = $this->cartClient->addValidItems($cartChangeTransfer);

        $failedToMoveRequestCollectionTransfer = $this->getWishlistRequestCollectionToCartDiff(
            $wishlistMoveToCartRequestCollectionTransfer,
            $quoteTransfer
        );

        $wishlistItemCollectionTransfer = $this->getWishlistCollectionToRemove(
            $wishlistMoveToCartRequestCollectionTransfer,
            $failedToMoveRequestCollectionTransfer
        );

        $this->wishlistClient->deleteItemCollection($wishlistItemCollectionTransfer);

        return $failedToMoveRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestTransfer $wishlistMoveToCartRequestTransfer
     *
     * @return void
     */
    protected function assertRequestTransfer(WishlistMoveToCartRequestTransfer $wishlistMoveToCartRequestTransfer)
    {
        $wishlistMoveToCartRequestTransfer->requireSku();
        $wishlistMoveToCartRequestTransfer->requireWishlistItem();
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function storeItemInQuote($sku)
    {
        $cartItem = $this->createItemTransfer($sku);
        $quoteTransfer = $this->cartClient->addItem($cartItem);

        return $quoteTransfer;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer($sku, $quantity = 1)
    {
        return (new ItemTransfer())
            ->setSku($sku)
            ->setQuantity($quantity);
    }

    /**
     * @phpstan-return array<string, bool>
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function createExistingSkuIndex(QuoteTransfer $quoteTransfer)
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
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    protected function executeWishlistPostMoveToCartCollectionExpanderPlugins(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer,
        QuoteTransfer $quoteTransfer,
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
    ): WishlistMoveToCartRequestCollectionTransfer {
        foreach ($this->wishlistPostMoveToCartCollectionExpanderPlugins as $wishlistPostMoveToCartCollectionExpanderPlugin) {
            $wishlistMoveToCartRequestCollectionDiffTransfer = $wishlistPostMoveToCartCollectionExpanderPlugin->expand(
                $wishlistMoveToCartRequestCollectionTransfer,
                $quoteTransfer,
                $wishlistMoveToCartRequestCollectionDiffTransfer
            );
        }

        return $wishlistMoveToCartRequestCollectionDiffTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $failedWishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer
     */
    protected function executeWishlistCollectionToRemoveExpanderPlugins(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer,
        WishlistMoveToCartRequestCollectionTransfer $failedWishlistMoveToCartRequestCollectionTransfer,
        WishlistItemCollectionTransfer $wishlistItemCollectionTransfer
    ): WishlistItemCollectionTransfer {
        foreach ($this->wishlistCollectionToRemoveExpanderPlugins as $wishlistCollectionToRemoveExpanderPlugin) {
            $wishlistItemCollectionTransfer = $wishlistCollectionToRemoveExpanderPlugin->expand(
                $wishlistMoveToCartRequestCollectionTransfer,
                $failedWishlistMoveToCartRequestCollectionTransfer,
                $wishlistItemCollectionTransfer
            );
        }

        return $wishlistItemCollectionTransfer;
    }
}
