<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\StorageProvider;

use Generated\Shared\Transfer\ChangeTransfer;
use SprykerFeature\Zed\Cart\Business\Exception\InvalidArgumentException;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class InMemoryProvider implements StorageProviderInterface
{

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $increasedItems
     *
     * @return CartTransfer
     */
    public function increaseItems(CartTransfer $cart, ChangeTransfer $increasedItems)
    {
        return $this->addItems($cart, $increasedItems);
    }

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $change
     *
     * @return CartTransfer
     */
    public function addItems(CartTransfer $cart, ChangeTransfer $change)
    {
        $existingItems = $cart->getItems();
        foreach ($change->getItems() as $item) {
            $this->isValidQuantity($item);
            $existingItems->append($item);
        }

        return $cart;
    }

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $decreasedItems
     *
     * @return CartTransfer
     */
    public function decreaseItems(CartTransfer $cart, ChangeTransfer $decreasedItems)
    {
        return $this->removeItems($cart, $decreasedItems);
    }

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $change
     *
     * @return CartTransfer
     */
    public function removeItems(CartTransfer $cart, ChangeTransfer $change)
    {
        $existingItems = $cart->getItems();
        $cartIndex = $this->createCartIndex($existingItems);

        foreach ($change->getItems() as $item) {
            $this->isValidQuantity($item);

            if (isset($cartIndex[$item->getGroupKey()])) {
                $this->decreaseExistingItem($existingItems, $cartIndex[$item->getGroupKey()], $item);
            } else {
                $this->decreaseBySku($existingItems, $item);
            }
        }

        return $cart;
    }

    /**
     * @param \ArrayObject|ItemTransfer[] $cartItems
     *
     * @return array
     */
    protected function createCartIndex(\ArrayObject $cartItems)
    {
        $cartIndex = [];

        foreach ($cartItems as $key => $cartItem) {
            if (!empty($cartItem->getGroupKey())) {
                $cartIndex[$cartItem->getGroupKey()] = $key;
            }
        }

        return $cartIndex;
    }

    /**
     * @param ItemTransfer[] $existingItems
     * @param int $index
     * @param ItemTransfer $item
     *
     * @return void
     */
    private function decreaseExistingItem($existingItems, $index, $item)
    {
        $existingItem = $existingItems[$index];
        $newQuantity = $existingItem->getQuantity() - $item->getQuantity();

        if ($newQuantity > 0) {
            $existingItem->setQuantity($newQuantity);
        } else {
            unset($existingItems[$index]);
        }
    }

    /**
     * @param \ArrayObject $existingItems
     * @param ItemTransfer $changedItem
     *
     * @return void
     */
    protected function decreaseBySku(\ArrayObject $existingItems, ItemTransfer $changedItem)
    {
        foreach ($existingItems as $key => $cartIndexItem) {
            if ($cartIndexItem->getSku() === $changedItem->getSku()) {
                $this->decreaseExistingItem($existingItems, $key, $changedItem);

                return;
            }
        }
    }

    /**
     * @param ItemTransfer $item
     *
     * @return bool
     */
    protected function isValidQuantity(ItemTransfer $item)
    {
        if ($item->getQuantity() < 1) {
            throw new InvalidArgumentException(
                sprintf(
                    'Could not change cart item "%d" with "%d" as value.',
                    $item->getSku(),
                    $item->getQuantity()
                )
            );
        }

        return true;
    }

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $change
     *
     * @return CartTransfer
     */
    public function addCouponCode(CartTransfer $cart, ChangeTransfer $change)
    {
        $cart->addCouponCode($change->getCouponCode());
    }

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $change
     *
     * @return CartTransfer
     */
    public function removeCouponCode(CartTransfer $cart, ChangeTransfer $change)
    {
        $couponCodes = [];
        foreach ($cart->getCouponCodes() as $couponCode) {
            if ($couponCode !== $change->getCouponCode()) {
                $couponCodes[] = $couponCode;
            }
        }

        $cart->setCouponCodes($couponCodes);
    }

    /**
     * @param CartTransfer $cart
     *
     * @return CartTransfer
     */
    public function clearCouponCodes(CartTransfer $cart)
    {
        $cart->setCouponCodes([]);
    }

}
