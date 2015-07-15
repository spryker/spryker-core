<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Communication\Controller;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\ChangeInterface;
use SprykerFeature\Zed\Cart\Business\CartFacade;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method CartFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param ChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function addItemAction(ChangeInterface $cartChange)
    {
        return $this->getFacade()->addToCart($cartChange);
    }

    /**
     * @param ChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function increaseItemQuantityAction(ChangeInterface $cartChange)
    {
        return $this->getFacade()->increaseQuantity($cartChange);
    }

    /**
     * @param ChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function decreaseItemQuantityAction(ChangeInterface $cartChange)
    {
        return $this->getFacade()->decreaseQuantity($cartChange);
    }

    /**
     * @param ChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function removeItemAction(ChangeInterface $cartChange)
    {
        return $this->getFacade()->removeFromCart($cartChange);
    }

    /**
     * @param CartInterface $cart
     *
     * @return CartInterface
     */
    public function recalculateCart(CartInterface $cart)
    {
        return $this->getFacade()->recalculateCart($cart);
    }

}
