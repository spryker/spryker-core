<?php

namespace SprykerFeature\Client\Cart\Service;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\CartItemInterface;
use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use SprykerEngine\Client\Kernel\AbstractClient;
use SprykerFeature\Client\Cart\Service\Session\CartSessionInterface;
use SprykerFeature\Client\Cart\Service\Zed\CartStubInterface;

/**
 * @method CartDependencyContainer getDependencyContainer()
 */
class CartClient extends AbstractClient implements CartClientInterface
{

    /**
     * @return CartInterface
     */
    public function getCart()
    {
        return $this->getSession()->getCart();
    }

    /**
     * @return CartSessionInterface
     */
    private function getSession()
    {
        return $this->getDependencyContainer()->createSession();
    }

    /**
     * @return CartInterface
     */
    public function clearCart()
    {
        $cart = new CartTransfer();

        $this->getSession()
            ->setItemCount(0)
            ->setCart($cart)
        ;

        return $cart;
    }

    /**
     * @return int
     */
    public function getItemCount()
    {
        return $this->getSession()->getItemCount();
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function addItem($sku, $quantity = 1)
    {
        $addedItem = $this->createChangedItem($sku, $quantity);
        $cartChange = $this->prepareCartChange($addedItem);
        $cart = $this->getStub()->addItem($cartChange);

        return $this->handleCartResponse($cart);
    }

    /**
     * @return CartStubInterface
     */
    private function getStub()
    {
        return $this->getDependencyContainer()->createStub();
    }

    /**
     * @param string $sku
     *
     * @return CartInterface
     */
    public function removeItem($sku)
    {
        $item = $this->getItemBySku($sku);
        $deletedItem = $this->createChangedItem($sku, $item->getQuantity());
        $cartChange = $this->prepareCartChange($deletedItem);
        $cart = $this->getStub()->removeItem($cartChange);

        return $this->handleCartResponse($cart);
    }

    /**
     *
     * @param string $sku
     *
     * @throws \InvalidArgumentException
     * @return CartItemInterface
     */
    private function getItemBySku($sku)
    {
        $cart = $this->getCart();

        foreach ($cart->getItems() as $item) {
            if ($item->getSku() === $sku) {
                return $item;
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

        $currentItem = $this->getItemBySku($sku);
        if ($currentItem->getQuantity() > $quantity) {
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
        $decreasedItem = $this->createChangedItem($sku, $quantity);
        $cartChange = $this->prepareCartChange($decreasedItem);
        $cart = $this->getStub()->decreaseItemQuantity($cartChange);

        return $this->handleCartResponse($cart);
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function increaseItemQuantity($sku, $quantity = 1)
    {
        $increasedItem = $this->createChangedItem($sku, $quantity);
        $cartChange = $this->prepareCartChange($increasedItem);
        $cart = $this->getStub()->increaseItemQuantity($cartChange);

        return $this->handleCartResponse($cart);
    }

    /**
     * @return CartInterface
     */
    public function recalculate()
    {
        $cart = $this->getCart();
        $cart = $this->getStub()->recalculate($cart);

        return $this->handleCartResponse($cart);
    }

    /**
     * @return ChangeTransfer
     */
    private function createCartChange()
    {
        $cart = $this->getCart();
        $cartChange = new ChangeTransfer();
        // @todo get cart hash
//        $cartChange->setCartHash($cart);

        return $cartChange;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartItemTransfer
     */
    private function createChangedItem($sku, $quantity = 1)
    {
        $changedItem = new CartItemTransfer();

        $changedItem->setId($sku);
        $changedItem->setSku($sku);
        $changedItem->setQuantity($quantity);

        return $changedItem;
    }

    /**
     * @param CartItemInterface $changedItem
     *
     * @return ChangeTransfer
     */
    private function prepareCartChange(CartItemInterface $changedItem)
    {
        $cartChange = $this->createCartChange();
        $cartChange->addItem($changedItem);

        return $cartChange;
    }

    /**
     * @param string $coupon
     *
     * @return CartInterface
     */
    public function addCoupon($coupon)
    {
        $cartChange = $this->createCartChange();
        $cartChange->setCouponCode($coupon);

        $cart = $this->getStub()->addCoupon($cartChange);

        return $this->handleCartResponse($cart);
    }

    /**
     * @param string $coupon
     *
     * @return CartInterface
     */
    public function removeCoupon($coupon)
    {
        $cartChange = $this->createCartChange();
        $cartChange->setCouponCode($coupon);

        $cart = $this->getStub()->removeCoupon($cartChange);

        return $this->handleCartResponse($cart);
    }

    /**
     * @return CartInterface
     */
    public function clearCoupons()
    {
        $cartChange = $this->createCartChange();
        $cart = $this->getStub()->clearCoupons($cartChange);

        return $this->handleCartResponse($cart);
    }

    /**
     * @param CartInterface $cart
     *
     * @return CartInterface
     */
    private function handleCartResponse(CartInterface $cart)
    {
        $this->getSession()->setCart($cart);

        return $cart;
    }

}
