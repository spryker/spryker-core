<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Cart;

use Generated\Shared\Transfer\ItemTransfer;
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
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestTransfer $wishlistMoveToCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestTransfer
     */
    public function moveToCart(WishlistMoveToCartRequestTransfer $wishlistMoveToCartRequestTransfer)
    {
        $this->assertRequestTransfer($wishlistMoveToCartRequestTransfer);

        $this->storeItemInQuote($wishlistMoveToCartRequestTransfer->getSku());
        $this->wishlistClient->removeItem($wishlistMoveToCartRequestTransfer->getWishlistItem());

        return $wishlistMoveToCartRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     *
     * @return void
     */
    public function moveCollectionToCart(WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer)
    {
        $itemTransfers = [];
        $wishlistItemCollectionTransfer = new WishlistItemCollectionTransfer();

        foreach ($wishlistMoveToCartRequestCollectionTransfer->getRequests() as $wishlistMoveToCartRequestTransfer) {
            $this->assertRequestTransfer($wishlistMoveToCartRequestTransfer);

            $itemTransfers[] = $this->createItemTransfer($wishlistMoveToCartRequestTransfer->getSku());
            $wishlistItemCollectionTransfer->addItem($wishlistMoveToCartRequestTransfer->getWishlistItem());
        }

        $quoteTransfer = $this->cartClient->addItems($itemTransfers);
        $this->cartClient->storeQuote($quoteTransfer);
        $this->wishlistClient->removeItemCollection($wishlistItemCollectionTransfer);
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
     * @return void
     */
    protected function storeItemInQuote($sku)
    {
        $cartItem = $this->createItemTransfer($sku);
        $quoteTransfer = $this->cartClient->addItem($cartItem);
        $this->cartClient->storeQuote($quoteTransfer);
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
