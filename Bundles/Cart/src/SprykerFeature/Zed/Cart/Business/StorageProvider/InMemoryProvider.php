<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\StorageProvider;

use Generated\Shared\Cart\ChangeInterface;
use SprykerFeature\Zed\Cart\Business\Exception\InvalidArgumentException;
use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\CartItemInterface;

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
        $cartIndex = $this->createCartIndex($existingItems);

        foreach ($change->getItems() as $item) {
            $this->isValidQuantity($item);

            if (isset($cartIndex[$item->getGroupKey()])) {
                $existingItem = $existingItems->offsetGet($cartIndex[$item->getGroupKey()]);
                $existingItem->setQuantity($existingItem->getQuantity() + $item->getQuantity());
            } else {
                $existingItems->append($item);
            }
        }

        return $cart;
    }

    /**
     * @param \ArrayObject|CartItemInterface[] $cartItems
     *
     * @return array
     */
    protected function createCartIndex(\ArrayObject $cartItems)
    {
        $cartIndex = [];

        foreach ($cartItems as $index => $cartItem) {
            if (!empty($cartItem->getGroupKey())) {
                $cartIndex[$cartItem->getGroupKey()] = $index;
            }
        }

        return $cartIndex;
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
     * @param CartItemInterface[] $existingItems
     * @param integer $index
     * @param CartItemInterface $item
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
     * @param CartItemInterface[] $existingItems
     * @param CartItemInterface $changedItem
     */
    protected function decreaseBySku(array $existingItems, CartItemInterface $changedItem)
    {
        foreach ($existingItems as $index => $cartIndexItem) {
            if ($cartIndexItem->getSku() == $changedItem->getSku()) {
                $this->decreaseExistingItem($existingItems, $index, $changedItem);
                return;
            }
        }
    }

    /**
     * @param CartItemInterface $item
     *
     * @return bool
     */
    protected function isValidQuantity(CartItemInterface $item)
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

}
