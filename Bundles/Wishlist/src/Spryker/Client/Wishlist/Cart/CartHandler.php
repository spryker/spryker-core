<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Cart;

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
     * @param \Spryker\Client\Wishlist\Dependency\Client\WishlistToCartInterface $cartClient
     * @param \Spryker\Client\Wishlist\WishlistClientInterface $wishlistClient
     */
    public function __construct(WishlistToCartInterface $cartClient, WishlistClientInterface $wishlistClient)
    {
        $this->cartClient = $cartClient;
        $this->wishlistClient = $wishlistClient;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $requestCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    protected function getWishlistRequestCollectionToCartDiff(WishlistMoveToCartRequestCollectionTransfer $requestCollectionTransfer, QuoteTransfer $quoteTransfer)
    {
        $wishlistRequestCollectionDiff = new WishlistMoveToCartRequestCollectionTransfer();

        $skuItems = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $skuItems[] = $itemTransfer->getSku();
        }

        foreach ($requestCollectionTransfer->getRequests() as $wishlistRequestTransfer) {
            if (in_array($wishlistRequestTransfer->getSku(), $skuItems)) {
                continue;
            }

            $wishlistRequestCollectionDiff->addRequest($wishlistRequestTransfer);
        }

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

        return $successfulRequestCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    public function moveCollectionToCart(WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer)
    {
        $itemTransfers = [];

        foreach ($wishlistMoveToCartRequestCollectionTransfer->getRequests() as $wishlistMoveToCartRequestTransfer) {
            $this->assertRequestTransfer($wishlistMoveToCartRequestTransfer);
            $itemTransfers[] = $this->createItemTransfer($wishlistMoveToCartRequestTransfer->getSku());
        }

        $quoteTransfer = $this->cartClient->addItems($itemTransfers);
        $this->cartClient->storeQuote($quoteTransfer);

        $failedToMoveRequestCollectionTransfer = $this->getWishlistRequestCollectionToCartDiff(
            $wishlistMoveToCartRequestCollectionTransfer,
            $quoteTransfer
        );

        $wishlistItemCollectionTransfer = $this->getWishlistCollectionToRemove(
            $wishlistMoveToCartRequestCollectionTransfer,
            $failedToMoveRequestCollectionTransfer
        );

        $this->wishlistClient->removeItemCollection($wishlistItemCollectionTransfer);

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
        $this->cartClient->storeQuote($quoteTransfer);

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
}
