<?php

namespace SprykerFeature\Zed\Cart\Business;

use SprykerFeature\Shared\Cart\Transfer\CartChange;
use SprykerFeature\Shared\Cart\Transfer\StepStorage;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * Class CartFacade
 * @package SprykerFeature\Zed\Cart\Business
 */
class CartFacade extends AbstractFacade
{

    /**
     * @param CartChange $cart
     * @return \SprykerEngine\Zed\Kernel\Business\ModelResult
     */
    public function addItems(CartChange $cart)
    {
        return $this->getDependencyContainer()->createCartModel()->addItems($cart);
    }

    /**
     * @param CartChange $cart
     * @return \SprykerEngine\Zed\Kernel\Business\ModelResult
     */
    public function removeItems(CartChange $cart)
    {
        return $this->getDependencyContainer()->createCartModel()->removeItems($cart);
    }

    /**
     * @param CartChange $cart
     * @return \SprykerEngine\Zed\Kernel\Business\ModelResult
     */
    public function changeQuantity(CartChange $cart)
    {
        return $this->getDependencyContainer()->createCartModel()->changeQuantity($cart);
    }

    /**
     * @param CartChange $cart
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function mergeGuestCartWithCustomerCart(CartChange $cart)
    {
        return $cart->getOrder();
        //return $this->factory->createModelCartStorage()->mergeGuestCartWithCustomerCart($cart);
    }

    /**
     * @param CartChange $cart
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function loadGuestCartByHash(CartChange $cart)
    {
        return $cart->getOrder();
       // return $this->factory->createModelCartStorage()->loadGuestCartByHash($cart);
    }

    /**
     * @param  CartChange                           $cart
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function clearCartStorage(CartChange $cart)
    {
        return $cart->getOrder();
        //return $this->factory->createModelCartStorage()->clearCartStorage($cart);
    }

    /**
     * @param StepStorage $transfer
     */
    public function storeUserCartStep(StepStorage $transfer)
    {
        //$this->factory->createModelCartStep()->storeCartStep($transfer);
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerEngine\Zed\Kernel\Business\ModelResult
     */
    public function addCouponCode(CartChange $cartChange)
    {
        return $this->getDependencyContainer()->createCouponCodeModel()->addCouponCode($cartChange);
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerEngine\Zed\Kernel\Business\ModelResult
     */
    public function removeCouponCode(CartChange $cartChange)
    {
        return $this->getDependencyContainer()->createCouponCodeModel()->removeCouponCode($cartChange);
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerEngine\Zed\Kernel\Business\ModelResult
     */
    public function clearCouponCodes(CartChange $cartChange)
    {
        return $this->getDependencyContainer()->createCouponCodeModel()->clearCouponCodes($cartChange);
    }
}
