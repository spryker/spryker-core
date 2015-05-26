<?php

namespace SprykerFeature\Yves\Cart\Provider;

use Generated\Shared\Cart\CartItemsInterface;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Yves\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Sdk\Cart\StorageProvider\StorageProviderInterface;
use SprykerFeature\Shared\Cart\Transfer\ItemInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionStorageProvider implements StorageProviderInterface
{
    const CART_SESSION_IDENTIFIER = 'cart';
    const CART_COUNT_SESSION_IDENTIFIER = 'cart_count';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var LocatorLocatorInterface
     */
    private $locator;

    /**
     * @param LocatorLocatorInterface $locator
     * @param SessionInterface $session
     */
    public function __construct(LocatorLocatorInterface $locator, SessionInterface $session)
    {
        $this->session = $session;
        $this->locator = $locator;
    }

    /**
     * @return CartInterface|mixed
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
     */
    public function setCart(CartInterface $cart)
    {
        $this->session->set(self::CART_SESSION_IDENTIFIER, $cart);

        $cartCount = $this->calculateCount($cart->getItems());
        $this->session->set(self::CART_COUNT_SESSION_IDENTIFIER, $cartCount);
    }

    /**
     * @return int
     */
    public function getCartCount()
    {
        if (!$this->session->has(self::CART_COUNT_SESSION_IDENTIFIER)) {
            return 0;
        }

        return $this->session->get(self::CART_COUNT_SESSION_IDENTIFIER);
    }

    /**
     * @param CartItemsInterface $items
     *
     * @return int
     */
    protected function calculateCount(CartItemsInterface $items)
    {
        $cartCount = 0;

        /** @var ItemInterface $item */
        foreach ($items as $item) {
            $cartCount += $item->getQuantity();
        }

        return $cartCount;
    }

    /**
     * @return LocatorLocatorInterface|AutoCompletion
     */
    protected function getLocator()
    {
        return $this->locator;
    }

}
