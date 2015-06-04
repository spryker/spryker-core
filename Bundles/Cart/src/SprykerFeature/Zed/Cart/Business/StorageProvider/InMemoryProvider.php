<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\StorageProvider;

use SprykerFeature\Zed\Cart\Business\Exception\InvalidArgumentException;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\CartItemInterface;
use Generated\Shared\Cart\CartItemsInterface;
use Generated\Shared\Transfer\CartItemTransfer;

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
        $skuIndex = $this->createSkuIndex($existingItems);

        /** @var ItemExpanderPluginInterface $item */
        foreach ($addedItems->getCartItems() as $item) {
            if ($item->getQuantity() < 1) {
                throw new InvalidArgumentException(
                    sprintf('Could not increase cart item %d with %d as value', $item->getId(), $item->getQuantity())
                );
            }

            if (isset($skuIndex[$item->getId()])) {
                /** @var CartItemInterface $existingItem */
                $existingItem = $existingItems->offsetGet($skuIndex[$item->getId()]);
                $existingItem->setQuantity($existingItem->getQuantity() + $item->getQuantity());
            } else {
                $existingItems->append($item);
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
        foreach ($removedItems as $item) {
            if ($item->getQuantity() < 1) {
                throw new InvalidArgumentException(
                    sprintf('Could not decrease cart item %d with %d as value', $item->getId(), $item->getQuantity())
                );
            }

            if ($existingItems->offsetExists($item->getId())) {
                $this->decreaseExistingItem($existingItems, $item);
            }
        }

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
     * @param CartItemsInterface|CartItemInterface[] $existingItems
     * @param CartItemInterface $item
     */
    private function decreaseExistingItem($existingItems, $item)
    {
        /** @var CartItemInterface $existingItem */
        $existingItem = $existingItems->offsetGet($item->getId());
        $newQuantity = $existingItem->getQuantity() - $item->getQuantity();

        if ($newQuantity > 0) {
            $existingItem->setQuantity($newQuantity);
        } else {
            $existingItems->offsetUnset($item->getId());
        }
    }

    /**
     * Index cart items by SKU.
     *
     * @param \ArrayObject|CartItemInterface[]
     *
     * @return array
     */
    protected function createSkuIndex(\ArrayObject $cartItems)
    {
        $skuIndex = [];

        /** @var CartItemInterface $cartItem */
        foreach ($cartItems as $key => $cartItem) {
            $skuIndex[$cartItem->getId()] = $key;
        }

        return $skuIndex;
    }
}
