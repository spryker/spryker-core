<?php

namespace SprykerFeature\Zed\Cart2\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractSdkController;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\Cart2\Transfer\CartChangeInterface;
use SprykerFeature\Shared\Cart2\Transfer\CartInterface;
use SprykerFeature\Zed\Cart2\Business\Cart2Facade;

class SdkController extends AbstractSdkController
{
    /**
     * @var Cart2Facade
     */
    protected $facade;

    /**
     * @param \Pimple $application
     * @param Factory $factory
     * @param Locator $locator
     */
    public function __construct(\Pimple $application, Factory $factory, Locator $locator)
    {
        parent::__construct($application, $factory, $locator);
        $this->facade = $this->getLocator()->cart2()->facade();
    }

    /**
     * @param CartChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function addItemAction(CartChangeInterface $cartChange)
    {
        return $this->facade->addToCart($cartChange);
    }

    /**
     * @param CartChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function increaseItemQuantityAction(CartChangeInterface $cartChange)
    {
        return $this->facade->increaseQuantity($cartChange);
    }

    /**
     * @param CartChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function decreaseItemQuantityAction(CartChangeInterface $cartChange)
    {
        return $this->facade->decreaseQuantity($cartChange);
    }

    /**
     * @param CartChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function removeItemAction(CartChangeInterface $cartChange)
    {
        return $this->facade->removeFromCart($cartChange);
    }

    /**
     * @param CartInterface $cart
     *
     * @return CartInterface
     */
    public function recalculateCart(CartInterface $cart)
    {
        return $this->facade->recalculateCart($cart);
    }
}
