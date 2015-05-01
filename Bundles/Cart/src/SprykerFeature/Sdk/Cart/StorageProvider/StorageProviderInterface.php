<?php

namespace SprykerFeature\Sdk\Cart\StorageProvider;

use SprykerFeature\Shared\Cart\Transfer\CartInterface;

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