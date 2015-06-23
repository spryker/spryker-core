<?php

namespace SprykerFeature\Client\Cart\Service\Session;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Transfer\CartTransfer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartSession implements CartSessionInterface
{

    const CART_SESSION_IDENTIFIER = 'cart session identifier';
    const CART_SESSION_ITEM_COUNT_IDENTIFIER = 'cart item count session identifier';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return CartInterface
     */
    public function getCart()
    {
        $cartTransfer = new CartTransfer();

        if ($this->session->has(self::CART_SESSION_IDENTIFIER)) {
            return $this->session->get(self::CART_SESSION_IDENTIFIER, $cartTransfer);
        }

        return $cartTransfer;
    }

    /**
     * @param CartInterface $cart
     *
     * @return $this
     */
    public function setCart(CartInterface $cart)
    {
        $this->session->set(self::CART_SESSION_IDENTIFIER, $cart);

        return $this;
    }

    /**
     * @return int
     */
    public function getItemCount()
    {
        if (!$this->session->has(self::CART_SESSION_ITEM_COUNT_IDENTIFIER)) {
            return 0;
        }

        return $this->session->get(self::CART_SESSION_ITEM_COUNT_IDENTIFIER);
    }

    /**
     * @param $itemCount
     *
     * @return CartStorageInterface
     */
    public function setItemCount($itemCount)
    {
        $this->session->set(self::CART_SESSION_ITEM_COUNT_IDENTIFIER, $itemCount);

        return $this;
    }

}
