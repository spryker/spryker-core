<?php

namespace SprykerFeature\Client\Cart\Storage;

use Generated\Shared\Cart\CartInterface;

interface CartStorageInterface
{

    /**
     * @return CartInterface
     */
    public function getCart();

    /**
     * @param CartInterface $cart
     */
    public function setCart(CartInterface $cart);

}
