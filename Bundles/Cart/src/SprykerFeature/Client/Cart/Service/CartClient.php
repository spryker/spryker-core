<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart\Service;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\CartItemInterface;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerFeature\Client\Cart\Service\Session\CartSessionInterface;
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
     * @param CartItemInterface $cartItemTransfer
     *
     * @return CartInterface
     */
    public function removeItem(CartItemInterface $cartItemTransfer)
    {
        $cartItemTransfer = $this->mergeCartItems(
            $cartItemTransfer,
            $this->getItemByIdentifier($cartItemTransfer->getSku())
        );

        $changeTransfer = $this->prepareCartChange($cartItemTransfer);
        $cartTransfer = $this->getZedStub()->removeItem($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @param int $identifier
     *
     * @throws \InvalidArgumentException
     *
     * @return CartItemInterface
     */
    private function getItemByIdentifier($identifier)
    {
        $cartTransfer = $this->getCart();

        foreach ($cartTransfer->getItems() as $cartItemTransfer) {
            if ($cartItemTransfer->getSku() === $identifier) {
                $existingCopy = clone $cartItemTransfer;
                $existingCopy->setGroupKey(null);
                return $existingCopy;
            }
        }

        throw new \InvalidArgumentException('No item with identifier "' . $identifier . '" found in cart');
    }

    /**
     * @param CartItemInterface $cartItemTransfer
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function changeItemQuantity(CartItemInterface $cartItemTransfer, $quantity = 1)
    {
        if ($quantity === 0) {
            return $this->removeItem($cartItemTransfer);
        }

        $cartItemTransfer = $this->getItemByIdentifier($cartItemTransfer->getSku());
        if ($cartItemTransfer->getQuantity() > $quantity) {
            return $this->decreaseItemQuantity($cartItemTransfer, $quantity);
        } else {
            return $this->increaseItemQuantity($cartItemTransfer, $quantity);
        }
    }

    /**
     * @param CartItemInterface $cartItemTransfer
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function decreaseItemQuantity(CartItemInterface $cartItemTransfer, $quantity = 1)
    {
        $changeTransfer = $this->createChangeTransferWithAdjustedQuantity($cartItemTransfer, $quantity);

        $cartTransfer = $this->getZedStub()->decreaseItemQuantity($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @param CartItemInterface $cartItemTransfer
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function increaseItemQuantity(CartItemInterface $cartItemTransfer, $quantity = 1)
    {
        $changeTransfer = $this->createChangeTransferWithAdjustedQuantity($cartItemTransfer, $quantity);

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
     * @param CartItemInterface $cartItemTransfer
     * @param int $quantity
     *
     * @return ChangeTransfer
     */
    private function createChangeTransferWithAdjustedQuantity(CartItemInterface $cartItemTransfer, $quantity)
    {
        $cartItemTransfer = $this->mergeCartItems(
            $cartItemTransfer,
            $this->getItemByIdentifier($cartItemTransfer->getSku())
        );

        $cartItemTransfer->setQuantity($quantity);

        return $this->prepareCartChange($cartItemTransfer);
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

    /**
     * @param CartItemInterface $newCartItemTransfer
     * @param CartItemInterface $oldCartItemByIdentifier
     *
     * @return CartItemInterface
     */
    private function mergeCartItems(CartItemInterface $newCartItemTransfer, CartItemInterface $oldCartItemByIdentifier)
    {
        $newCartItemTransfer->fromArray(
            $oldCartItemByIdentifier->toArray()
        );

        return $newCartItemTransfer;
    }

}
