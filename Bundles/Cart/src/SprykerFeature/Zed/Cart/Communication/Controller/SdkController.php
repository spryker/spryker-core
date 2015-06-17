<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractClientController;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use Generated\Shared\Transfer\CartCartChangeInterfaceTransfer;
use Generated\Shared\Transfer\CartCartInterfaceTransfer;
use SprykerFeature\Zed\Cart\Business\CartFacade;

class ClientController extends AbstractClientController
{
    /**
     * @var CartFacade
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
        $this->facade = $this->getFacade();
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
