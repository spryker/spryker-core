<?php

namespace SprykerFeature\Sdk\Cart2\StorageProvider;

use Generated\Shared\Transfer\Cart2CartInterfaceTransfer;

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
