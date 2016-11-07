<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Cart;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestTransfer;
use Spryker\Client\Cart\CartClientInterface;
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
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     * @param \Spryker\Client\Wishlist\WishlistClientInterface $wishlistClient
     */
    public function __construct(CartClientInterface $cartClient, WishlistClientInterface $wishlistClient)
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

        $wishlistItem = $wishlistMoveToCartRequestTransfer->getWishlistItem();
        $wishlistItem->requireFkCustomer();
        $wishlistItem->requireFkProduct();

        $cartItem = (new ItemTransfer())
            ->setSku($wishlistMoveToCartRequestTransfer->getSku())
            ->setQuantity(1);

        $quoteTransfer = $this->cartClient->addItem($cartItem);
        $this->cartClient->storeQuote($quoteTransfer);

        $this->wishlistClient->removeItem($wishlistItem);

        return $wishlistMoveToCartRequestTransfer;
    }

}
