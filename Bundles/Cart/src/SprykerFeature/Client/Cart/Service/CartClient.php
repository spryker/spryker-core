<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart\Service;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\CartItemInterface;
use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerFeature\Client\Cart\Service\Session\CartSessionInterface;
use SprykerFeature\Client\Cart\Service\Storage\CartStorageInterface;
use SprykerFeature\Client\Cart\Service\Zed\CartStubInterface;

/**
 * @method CartDependencyContainer getDependencyContainer()
 */
class CartClient extends AbstractClient implements CartClientInterface
{

    /**
     * @return CartInterface|CartTransfer
     */
    public function getCart()
    {
        $cart = $this->getSession()->getCart();
        foreach ($cart->getItems() as $cartItem) {
            $product = $this->getStorage()->getProduct($cartItem->getId());
            $cartItem->setName($product['abstract_name']);
        }

        return $cart;
    }

    /**
     * @return CartSessionInterface
     */
    private function getSession()
    {
        return $this->getDependencyContainer()->createSession();
    }

    /**
     * @return CartStorageInterface
     */
    private function getStorage()
    {
        return $this->getDependencyContainer()->createStorage();
    }

    /**
     * @return CartInterface
     */
    public function clearCart()
    {
        $cartTransfer = new CartTransfer();

        $this->getSession()
            ->setItemCount(0)
            ->setCart($cartTransfer)
        ;

        return $cartTransfer;
    }

    /**
     * @return int
     */
    public function getItemCount()
    {
        return $this->getSession()->getItemCount();
    }

    /**
     * @param CartItemInterface $cartItemTransfer
     *
     * @return CartInterface
     */
    public function addItem(CartItemInterface $cartItemTransfer)
    {
        $changeTransfer = $this->prepareCartChange($cartItemTransfer);
        $cartTransfer = $this->getZedStub()->addItem($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @return CartStubInterface
     */
    private function getZedStub()
    {
        return $this->getDependencyContainer()->createZedStub();
    }

    /**
     * @param string $sku
     *
     * @return CartInterface
     */
    public function removeItem($sku)
    {
        $cartItemTransfer = $this->getItemBySku($sku);
        $cartItemTransfer = $this->createChangedItem($sku, $cartItemTransfer->getQuantity());
        $changeTransfer = $this->prepareCartChange($cartItemTransfer);
        $cartTransfer = $this->getZedStub()->removeItem($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @param string $sku
     *
     * @throws \InvalidArgumentException
     *
     * @return CartItemInterface
     */
    private function getItemBySku($sku)
    {
        $cartTransfer = $this->getCart();

        foreach ($cartTransfer->getItems() as $cartItemTransfer) {
            if ($cartItemTransfer->getSku() === $sku) {
                return $cartItemTransfer;
            }
        }

        throw new \InvalidArgumentException('No item with sku "' . $sku . '" found in cart');
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function changeItemQuantity($sku, $quantity = 1)
    {
        if ($quantity === 0) {
            return $this->removeItem($sku);
        }

        $cartItemTransfer = $this->getItemBySku($sku);
        if ($cartItemTransfer->getQuantity() > $quantity) {
            return $this->decreaseItemQuantity($sku, $quantity);
        } else {
            return $this->increaseItemQuantity($sku, $quantity);
        }
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function decreaseItemQuantity($sku, $quantity = 1)
    {
        $cartItemTransfer = $this->createChangedItem($sku, $quantity);
        $changeTransfer = $this->prepareCartChange($cartItemTransfer);
        $cartTransfer = $this->getZedStub()->decreaseItemQuantity($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function increaseItemQuantity($sku, $quantity = 1)
    {
        $cartItemTransfer = $this->createChangedItem($sku, $quantity);
        $changeTransfer = $this->prepareCartChange($cartItemTransfer);
        $cartTransfer = $this->getZedStub()->increaseItemQuantity($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @return CartInterface
     */
    public function recalculate()
    {
        $cartTransfer = $this->getCart();
        $cartTransfer = $this->getZedStub()->recalculate($cartTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @return ChangeTransfer
     */
    private function createCartChange()
    {
        $cartTransfer = $this->getCart();
        $changeTransfer = new ChangeTransfer();
        $changeTransfer->setCart($cartTransfer);

        return $changeTransfer;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartItemTransfer
     */
    private function createChangedItem($sku, $quantity = 1)
    {
        $cartItemTransfer = new CartItemTransfer();

        $cartItemTransfer->setId($sku);
        $cartItemTransfer->setSku($sku);
        $cartItemTransfer->setQuantity($quantity);

        return $cartItemTransfer;
    }

    /**
     * @param CartItemInterface $cartItemTransfer
     *
     * @return ChangeTransfer
     */
    private function prepareCartChange(CartItemInterface $cartItemTransfer)
    {
        $changeTransfer = $this->createCartChange();
        $changeTransfer->addItem($cartItemTransfer);

        return $changeTransfer;
    }

    /**
     * @param string $coupon
     *
     * @return CartInterface
     */
    public function addCoupon($coupon)
    {
        $changeTransfer = $this->createCartChange();
        $changeTransfer->setCouponCode($coupon);

        $cartTransfer = $this->getZedStub()->addCoupon($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @param string $coupon
     *
     * @return CartInterface
     */
    public function removeCoupon($coupon)
    {
        $changeTransfer = $this->createCartChange();
        $changeTransfer->setCouponCode($coupon);

        $cartTransfer = $this->getZedStub()->removeCoupon($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @return CartInterface
     */
    public function clearCoupons()
    {
        $changeTransfer = $this->createCartChange();
        $cartTransfer = $this->getZedStub()->clearCoupons($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @param CartInterface $cartTransfer
     *
     * @return CartInterface
     */
    private function handleCartResponse(CartInterface $cartTransfer)
    {
        $this->getSession()->setCart($cartTransfer);

        return $cartTransfer;
    }

}
