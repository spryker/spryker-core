<?php

namespace SprykerFeature\Zed\Cart\Business\StorageProvider;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Shared\Cart\Transfer\CartInterface;
use SprykerFeature\Shared\Cart\Transfer\ItemCollectionInterface;
use SprykerFeature\Shared\Cart\Transfer\ItemInterface;
use SprykerFeature\Zed\Cart\Business\Exception\InvalidArgumentException;

class InMemoryProvider implements StorageProviderInterface
{
    /**
     * @param CartInterface $cart
     * @param ItemCollectionInterface $addedItems
     *
     * @return CartInterface
     */
    public function addItems(CartInterface $cart, ItemCollectionInterface $addedItems)
    {
        $existingItems = $cart->getItems();

        /** @var AbstractTransfer|ItemInterface $item */
        foreach ($addedItems as $item) {
            if ($item->getQuantity() < 1) {
                throw new InvalidArgumentException(
                    sprintf('Could not increase cart item %d with %d as value', $item->getId(), $item->getQuantity())
                );
            }

            if ($existingItems->offsetExists($item->getId())) {
                /** @var ItemInterface $existingItem */
                $existingItem = $existingItems->offsetGet($item->getId());
                $existingItem->setQuantity($existingItem->getQuantity() + $item->getQuantity());
            } else {
                $existingItems->add($item);
            }
        }

        return $cart;
    }

    /**
     * @param CartInterface $cart
     * @param ItemCollectionInterface $removedItems
     *
     * @return CartInterface
     */
    public function removeItems(CartInterface $cart, ItemCollectionInterface $removedItems)
    {
        $existingItems = $cart->getItems();

        /** @var AbstractTransfer|ItemInterface $item */
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
     * @param ItemCollectionInterface $increasedItems
     *
     * @return CartInterface
     */
    public function increaseItems(CartInterface $cart, ItemCollectionInterface $increasedItems)
    {
        return $this->addItems($cart, $increasedItems);
    }

    /**
     * @param CartInterface $cart
     * @param ItemCollectionInterface $decreasedItems
     *
     * @return CartInterface
     */
    public function decreaseItems(CartInterface $cart, ItemCollectionInterface $decreasedItems)
    {
        return $this->removeItems($cart, $decreasedItems);
    }


    /**
     * @param ItemInterface[]|ItemCollectionInterface $existingItems
     * @param ItemInterface $item
     *
     */
    private function decreaseExistingItem($existingItems, $item)
    {
        /** @var ItemInterface $existingItem */
        $existingItem = $existingItems->offsetGet($item->getId());
        $newQuantity = $existingItem->getQuantity() - $item->getQuantity();

        if ($newQuantity > 0) {
            $existingItem->setQuantity($newQuantity);
        } else {
            $existingItems->offsetUnset($item->getId());
        }
    }
}
