<?php

namespace SprykerFeature\Yves\Cart2\Provider;

use Generated\Yves\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;
use SprykerFeature\Sdk\Cart2\StorageProvider\StorageProviderInterface;
use SprykerFeature\Shared\Cart2\Transfer\CartInterface;
use SprykerFeature\Shared\Cart2\Transfer\ItemCollectionInterface;
use SprykerFeature\Shared\Cart2\Transfer\ItemInterface;
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
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @param LocatorInterface $locator
     * @param SessionInterface $session
     */
    public function __construct(LocatorInterface $locator, SessionInterface $session)
    {
        $this->session = $session;
        $this->locator = $locator;
    }

    /**
     * @return CartInterface
     */
    public function getCart()
    {
        $cartTransfer = $this->getLocator()->cart2()->transferCart();

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
     * @param ItemCollectionInterface $items
     *
     * @return int
     */
    protected function calculateCount(ItemCollectionInterface $items)
    {
        $cartCount = 0;

        /** @var ItemInterface $item */
        foreach ($items as $item) {
            $cartCount += $item->getQuantity();
        }

        return $cartCount;
    }

    /**
     * @return LocatorInterface|AutoCompletion
     */
    protected function getLocator()
    {
        return $this->locator;
    }

}