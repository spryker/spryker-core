<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\StorageProvider;

use Generated\Shared\Cart\ChangeInterface;
use SprykerFeature\Zed\Cart\Business\Exception\InvalidArgumentException;
use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\ItemInterface;

class InMemoryProvider implements StorageProviderInterface
{

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $increasedItems
     *
     * @return CartInterface
     */
    public function increaseItems(CartInterface $cart, ChangeInterface $increasedItems)
    {
        return $this->addItems($cart, $increasedItems);
    }

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    public function addItems(CartInterface $cart, ChangeInterface $change)
    {
        $existingItems = $cart->getItems();
        foreach ($change->getItems() as $item) {
            $this->isValidQuantity($item);
            $existingItems->append($item);
        }

        return $cart;
    }

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $decreasedItems
     *
     * @return CartInterface
     */
    public function decreaseItems(CartInterface $cart, ChangeInterface $decreasedItems)
    {
        return $this->removeItems($cart, $decreasedItems);
    }

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    public function removeItems(CartInterface $cart, ChangeInterface $change)
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
     * @param \ArrayObject|ItemInterface[] $cartItems
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
     * @param ItemInterface[] $existingItems
     * @param int $index
     * @param ItemInterface $item
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
     * @param ItemInterface $changedItem
     */
    protected function decreaseBySku(\ArrayObject $existingItems, ItemInterface $changedItem)
    {
        foreach ($existingItems as $key => $cartIndexItem) {
            if ($cartIndexItem->getSku() === $changedItem->getSku()) {
                $this->decreaseExistingItem($existingItems, $key, $changedItem);

                return;
            }
        }
    }

    /**
     * @param ItemInterface $item
     *
     * @return bool
     */
    protected function isValidQuantity(ItemInterface $item)
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
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    public function addCouponCode(CartInterface $cart, ChangeInterface $change)
    {
        $cart->addCouponCode($change->getCouponCode());
    }

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    public function removeCouponCode(CartInterface $cart, ChangeInterface $change)
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
     * @param CartInterface $cart
     *
     * @return CartInterface
     */
    public function clearCouponCodes(CartInterface $cart)
    {
        $cart->setCouponCodes([]);
    }

}
