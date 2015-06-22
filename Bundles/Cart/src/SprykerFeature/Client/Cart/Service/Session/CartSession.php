<?php

namespace SprykerFeature\Client\Cart\Storage;

use Generated\Shared\Cart\CartInterface;

class CartSession implements CartStorageInterface
{

    const CART_KEY = 'cart session key';

    /**
     * @var array
     */
    private $session;

    /**
     * @param array $session
     */
    public function __construct(array $session)
    {
        $this->session = $session;
    }

    /**
     * @return CartInterface
     */
    public function getCart()
    {

    }

    /**
     * @param CartInterface $cart
     */
    public function setCart(CartInterface $cart)
    {

    }

}
