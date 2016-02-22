<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\StorageProvider;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Cart\Business\Exception\InvalidArgumentException;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class InMemoryProvider implements StorageProviderInterface
{

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $increasedItems
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function increaseItems(CartTransfer $cart, ChangeTransfer $increasedItems)
    {
        return $this->addItems($cart, $increasedItems);
    }

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
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
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $decreasedItems
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function decreaseItems(CartTransfer $cart, ChangeTransfer $decreasedItems)
    {
        return $this->removeItems($cart, $decreasedItems);
    }

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
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
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $cartItems
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
     * @param \Generated\Shared\Transfer\ItemTransfer[] $existingItems
     * @param int $index
     * @param \Generated\Shared\Transfer\ItemTransfer $item
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
     * @param \Generated\Shared\Transfer\ItemTransfer $changedItem
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
     * @param \Generated\Shared\Transfer\ItemTransfer $item
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
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addCouponCode(CartTransfer $cart, ChangeTransfer $change)
    {
        $cart->addCouponCode($change->getCouponCode());

        return $cart;
    }

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
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

        return $cart;
    }

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCouponCodes(CartTransfer $cart)
    {
        $cart->setCouponCodes([]);

        return $cart;
    }

}
