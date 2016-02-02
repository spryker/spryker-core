<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method CartFactory getFactory()
 */
class CartClient extends AbstractClient implements CartClientInterface
{

    /**
     * @return CartTransfer|CartTransfer
     */
    public function getCart()
    {
        return $this->getSession()->getCart();
    }

    /**
     * @return \Spryker\Client\Cart\Session\CartSessionInterface
     */
    protected function getSession()
    {
        return $this->getFactory()->createSession();
    }

    /**
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCart()
    {
        $cartTransfer = new CartTransfer();

        $this->getSession()
            ->setItemCount(0)
            ->setCart($cartTransfer);

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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addItem(ItemTransfer $itemTransfer)
    {
        $changeTransfer = $this->prepareCartChange($itemTransfer);
        $cartTransfer = $this->getZedStub()->addItem($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @return \Spryker\Client\Cart\Zed\CartStubInterface
     */
    protected function getZedStub()
    {
        return $this->getFactory()->createZedStub();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeItem(ItemTransfer $itemTransfer)
    {
        $itemTransfer = $this->mergeCartItems(
            $itemTransfer,
            $this->findItem($itemTransfer)
        );

        $changeTransfer = $this->prepareCartChange($itemTransfer);
        $cartTransfer = $this->getZedStub()->removeItem($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemToFind
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function findItem(ItemTransfer $itemToFind)
    {
        $cartTransfer = $this->getCart();

        foreach ($cartTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $itemToFind->getSku()) {
                $matchingItemTransfer = clone $itemTransfer;  //@todo is clone still needed?
                return $matchingItemTransfer;
            }
        }

        throw new \InvalidArgumentException(
            sprintf('No item with sku "%s" found in cart.', $itemToFind->getSku())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function changeItemQuantity(ItemTransfer $itemTransfer, $quantity = 1)
    {
        if ($quantity === 0) {
            return $this->removeItem($itemTransfer);
        }

        $itemTransfer = $this->findItem($itemTransfer);
        $delta = abs($itemTransfer->getQuantity() - $quantity);

        if ($delta === 0) {
            return $this->getCart();
        }

        if ($itemTransfer->getQuantity() > $quantity) {
            return $this->decreaseItemQuantity($itemTransfer, $delta);
        }

        return $this->increaseItemQuantity($itemTransfer, $delta);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function decreaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1)
    {
        $changeTransfer = $this->createChangeTransferWithAdjustedQuantity($itemTransfer, $quantity);

        $cartTransfer = $this->getZedStub()->decreaseItemQuantity($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function increaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1)
    {
        $changeTransfer = $this->createChangeTransferWithAdjustedQuantity($itemTransfer, $quantity);

        $cartTransfer = $this->getZedStub()->increaseItemQuantity($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function recalculate()
    {
        $cartTransfer = $this->getCart();
        $cartTransfer = $this->getZedStub()->recalculate($cartTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    protected function createCartChange()
    {
        $cartTransfer = $this->getCart();
        $changeTransfer = new ChangeTransfer();
        $changeTransfer->setCart($cartTransfer);

        return $changeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    protected function prepareCartChange(ItemTransfer $itemTransfer)
    {
        $changeTransfer = $this->createCartChange();
        $changeTransfer->addItem($itemTransfer);

        return $changeTransfer;
    }

    /**
     * @param string $coupon
     *
     * @return \Generated\Shared\Transfer\CartTransfer
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
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeCoupon($coupon)
    {
        $changeTransfer = $this->createCartChange();
        $changeTransfer->setCouponCode($coupon);

        $cartTransfer = $this->getZedStub()->removeCoupon($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCoupons()
    {
        $changeTransfer = $this->createCartChange();
        $cartTransfer = $this->getZedStub()->clearCoupons($changeTransfer);

        return $this->handleCartResponse($cartTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    protected function createChangeTransferWithAdjustedQuantity(ItemTransfer $itemTransfer, $quantity)
    {
        $itemTransfer = $this->mergeCartItems(
            $itemTransfer,
            $this->findItem($itemTransfer)
        );

        $itemTransfer->setQuantity($quantity);

        return $this->prepareCartChange($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cartTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    protected function handleCartResponse(CartTransfer $cartTransfer)
    {
        $this->getSession()->setCart($cartTransfer);

        return $cartTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $newItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $oldItemByIdentifier
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function mergeCartItems(ItemTransfer $newItemTransfer, ItemTransfer $oldItemByIdentifier)
    {
        $newItemTransfer->fromArray($oldItemByIdentifier->toArray(), true);

        return $newItemTransfer;
    }

}
