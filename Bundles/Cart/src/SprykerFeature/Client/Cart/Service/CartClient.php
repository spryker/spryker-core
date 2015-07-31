<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart\Service;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\ItemInterface;
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
     * @param ItemInterface $itemTransfer
     *
     * @return CartInterface
     */
    public function addItem(ItemInterface $itemTransfer)
    {
        $changeTransfer = $this->prepareCartChange($itemTransfer);
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
     * @param ItemInterface $itemTransfer
     *
     * @return CartInterface
     */
    public function removeItem(ItemInterface $itemTransfer)
    {
        $itemTransfer = $this->mergeCartItems(
            $itemTransfer,
            $this->getItemByIdentifier($itemTransfer->getSku())
        );

        $changeTransfer = $this->prepareCartChange($itemTransfer);
        $cartTransfer = $this->getZedStub()->removeItem($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @param int $identifier
     *
     * @throws \InvalidArgumentException
     *
     * @return ItemInterface
     */
    private function getItemByIdentifier($identifier)
    {
        $cartTransfer = $this->getCart();

        foreach ($cartTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $identifier) {
                $existingItemTransfer = clone $itemTransfer;
                $existingItemTransfer->setGroupKey(null);
                return $existingItemTransfer;
            }
        }

        throw new \InvalidArgumentException('No item with identifier "' . $identifier . '" found in cart');
    }

    /**
     * @param ItemInterface $itemTransfer
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function changeItemQuantity(ItemInterface $itemTransfer, $quantity = 1)
    {
        if ($quantity === 0) {
            return $this->removeItem($itemTransfer);
        }

        $itemTransfer = $this->getItemByIdentifier($itemTransfer->getSku());
        if ($itemTransfer->getQuantity() > $quantity) {
            return $this->decreaseItemQuantity($itemTransfer, $quantity);
        } else {
            return $this->increaseItemQuantity($itemTransfer, $quantity);
        }
    }

    /**
     * @param ItemInterface $itemTransfer
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function decreaseItemQuantity(ItemInterface $itemTransfer, $quantity = 1)
    {
        $changeTransfer = $this->createChangeTransferWithAdjustedQuantity($itemTransfer, $quantity);

        $cartTransfer = $this->getZedStub()->decreaseItemQuantity($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @param ItemInterface $itemTransfer
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function increaseItemQuantity(ItemInterface $itemTransfer, $quantity = 1)
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
     * @param ItemInterface $itemTransfer
     *
     * @return ChangeTransfer
     */
    private function prepareCartChange(ItemInterface $itemTransfer)
    {
        $changeTransfer = $this->createCartChange();
        $changeTransfer->addItem($itemTransfer);

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
     * @param ItemInterface $itemTransfer
     * @param int $quantity
     *
     * @return ChangeTransfer
     */
    private function createChangeTransferWithAdjustedQuantity(ItemInterface $itemTransfer, $quantity)
    {
        $itemTransfer = $this->mergeCartItems(
            $itemTransfer,
            $this->getItemByIdentifier($itemTransfer->getSku())
        );

        $itemTransfer->setQuantity($quantity);

        return $this->prepareCartChange($itemTransfer);
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
     * @param ItemInterface $newItemTransfer
     * @param ItemInterface $oldItemByIdentifier
     *
     * @return ItemInterface
     */
    private function mergeCartItems(ItemInterface $newItemTransfer, ItemInterface $oldItemByIdentifier)
    {
        $newItemTransfer->fromArray(
            $oldItemByIdentifier->toArray()
        );

        return $newItemTransfer;
    }

}
