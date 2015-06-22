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
     *
     * @return CartStorageInterface
     */
    public function setCart(CartInterface $cart);

    /**
     * @return int
     */
    public function getItemCount();

    /**
     * @param $itemCount
     *
     * @return CartStorageInterface
     */
    public function setItemCount($itemCount);

}
