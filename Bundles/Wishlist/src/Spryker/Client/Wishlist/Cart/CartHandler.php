<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Cart;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestTransfer;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToCartInterface;
use Spryker\Client\Wishlist\WishlistClientInterface;

class CartHandler implements CartHandlerInterface
{

    /**
     * @var \Spryker\Client\Cart\CartClientInterface
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
        $wishlistMoveToCartRequestTransfer->requireSku();
        $wishlistMoveToCartRequestTransfer->requireWishlistItem();

        $this->handleCart($wishlistMoveToCartRequestTransfer->getSku());
        $this->wishlistClient->removeItem($wishlistMoveToCartRequestTransfer->getWishlistItem());

        return $wishlistMoveToCartRequestTransfer;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function handleCart($sku)
    {
        $cartItem = (new ItemTransfer())
            ->setSku($sku)
            ->setQuantity(1);

        $quoteTransfer = $this->cartClient->addItem($cartItem);
        $this->cartClient->storeQuote($quoteTransfer);
    }

}
