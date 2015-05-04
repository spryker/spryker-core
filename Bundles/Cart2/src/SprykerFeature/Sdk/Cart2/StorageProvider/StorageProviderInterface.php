<?php

namespace SprykerFeature\Sdk\Cart2\StorageProvider;

use SprykerFeature\Shared\Cart2\Transfer\CartInterface;

interface StorageProviderInterface
{
    /**
     * @return CartInterface
     */
    public function getCart();

    /**
     * @param CartInterface $cart
     */
    public function setCart(CartInterface $cart);

    /**
     * @return int
     */
    public function getCartCount();
}
