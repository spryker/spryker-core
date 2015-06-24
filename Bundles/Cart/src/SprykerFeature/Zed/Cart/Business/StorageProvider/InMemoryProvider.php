<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\StorageProvider;

use Generated\Shared\Transfer\CartItemsTransfer;
use SprykerFeature\Zed\Cart\Business\Exception\InvalidArgumentException;
use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\CartItemInterface;
use Generated\Shared\Cart\CartItemsInterface;

class InMemoryProvider implements StorageProviderInterface
{
    /**
     * @param CartInterface $cart
     * @param CartItemsInterface $addedItems
     *
     * @return CartInterface
     */
    public function addItems(CartInterface $cart, CartItemsInterface $addedItems)
    {
        $existingItems = $cart->getItems();

        /** @var CartItemInterface $item */
        foreach ($addedItems->getCartItems() as $item) {
            if ($item->getQuantity() < 1) {
                throw new InvalidArgumentException(
                    sprintf('Could not increase cart item %d with %d as value', $item->getSku(), $item->getQuantity())
                );
            }

            if ($this->hasItemBySku($existingItems, $item->getSku())) {
                /** @var CartItemInterface $existingItem */
                $existingItem = $this->getItemBySku($existingItems, $item->getSku());
                $existingItem->setQuantity($existingItem->getQuantity() + $item->getQuantity());
            } else {
                $existingItems->addCartItem($item);
            }
        }

        return $cart;
    }

    /**
     * @param CartInterface $cart
     * @param CartItemsInterface $removedItems
     *
     * @return CartInterface
     */
    public function removeItems(CartInterface $cart, CartItemsInterface $removedItems)
    {
        $existingItems = $cart->getItems();

        /** @var CartItemInterface $item */
        foreach ($removedItems->getCartItems() as $item) {
            if ($item->getQuantity() < 1) {
                throw new InvalidArgumentException(
                    sprintf('Could not decrease cart item %d with %d as value', $item->getSku(), $item->getQuantity())
                );
            }

            if ($this->hasItemBySku($existingItems, $item->getSku())) {
                $existingItems = $this->decreaseExistingItem($existingItems, $item);
            }
        }

        $cart->setItems($existingItems);

        return $cart;
    }

    /**
     * @param CartInterface $cart
     * @param CartItemsInterface $increasedItems
     *
     * @return CartInterface
     */
    public function increaseItems(CartInterface $cart, CartItemsInterface $increasedItems)
    {
        return $this->addItems($cart, $increasedItems);
    }

    /**
     * @param CartInterface $cart
     * @param CartItemsInterface $decreasedItems
     *
     * @return CartInterface
     */
    public function decreaseItems(CartInterface $cart, CartItemsInterface $decreasedItems)
    {
        return $this->removeItems($cart, $decreasedItems);
    }

    /**
     * @param CartItemsInterface $existingItems
     * @param CartItemInterface $item
     *
     * @return CartItemsInterface
     */
    private function decreaseExistingItem(CartItemsInterface $existingItems, CartItemInterface $item)
    {
        $existingItem = $this->getItemBySku($existingItems, $item->getSku());
        $newQuantity = $existingItem->getQuantity() - $item->getQuantity();

        if ($newQuantity > 0) {
            $existingItem->setQuantity($newQuantity);
        } else {
            $existingItems = $this->getCartItemsWithoutGivenSku($existingItems, $item->getSku());
        }

        return $existingItems;
    }

    /**
     * @param CartItemsInterface $existingItems
     * @param string $sku
     *
     * @throws \LogicException
     * @return CartItemInterface
     */
    private function getItemBySku(CartItemsInterface $existingItems, $sku)
    {
        foreach ($existingItems->getCartItems() as $item) {
            if ($item->getSku() === $sku) {
                return $item;
            }
        }

        throw new \LogicException('Can\'t find product with sku "' . $sku . '" in existing items');
    }

    /**
     * @param CartItemsInterface $existingItems
     * @param string $sku
     *
     * @return bool
     */
    private function hasItemBySku(CartItemsInterface $existingItems, $sku)
    {
        foreach ($existingItems->getCartItems() as $item) {
            if ($item->getSku() === $sku) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param CartItemsInterface $existingItems
     * @param string $sku
     *
     * @return CartItemsTransfer
     */
    private function getCartItemsWithoutGivenSku(CartItemsInterface $existingItems, $sku)
    {
        $items = new CartItemsTransfer();
        foreach ($existingItems->getCartItems() as $item) {
            if ($item->getSku() !== $sku) {
                $items->addCartItem($item);
            }
        }

        return $items;
    }

}
