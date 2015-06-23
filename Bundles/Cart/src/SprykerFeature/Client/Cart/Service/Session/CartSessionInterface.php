<?php

namespace SprykerFeature\Client\Cart\Service\Session;

use Generated\Shared\Cart\CartInterface;

interface CartSessionInterface
{

    /**
     * @return CartInterface
     */
    public function getCart();

    /**
     * @param CartInterface $cart
     *
     * @return $this
     */
    public function setCart(CartInterface $cart);

    /**
     * @return int
     */
    public function getItemCount();

    /**
     * @param $itemCount
     *
     * @return $this
     */
    public function setItemCount($itemCount);

}
