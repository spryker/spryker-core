<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Storage;

use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;

class InMemory extends BaseStorage implements StorageInterface
{
    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function addItems(WishlistChangeInterface $wishlistChange)
    {
        foreach ($wishlistChange->getItems() as $wishlistItem) {
            $this->wishlist->addItem($wishlistItem);
        }

        return $this->wishlist;
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function removeItems(WishlistChangeInterface $wishlistChange)
    {
        $wishlistIndex = $this->createIndex();
        foreach ($wishlistChange->getItems() as $wishlistItem) {

            if (isset($wishlistIndex[$wishlistItem->getGroupKey()])) {
                $this->decreaseExistingItem($wishlistIndex[$wishlistItem->getGroupKey()], $wishlistItem);
            } else {
                $this->decreaseByConcreteSku($wishlistItem);
            }
        }

        return $this->wishlist;
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function increaseItems(WishlistChangeInterface $wishlistChange)
    {
        return $this->addItems($wishlistChange);
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function decreaseItems(WishlistChangeInterface $wishlistChange)
    {
        return $this->removeItems($wishlistChange);
    }

    /**
     * @param WishlistItemInterface $wishlistItem
     */
    protected function decreaseByConcreteSku(WishlistItemInterface $wishlistItem)
    {
        foreach ($this->wishlist->getItems() as $key => $existingItem) {
            if ($existingItem->getConcreteSku() === $wishlistItem->getConcreteSku()) {
                $this->decreaseExistingItem($key, $wishlistItem);
                return;
            }
        }
    }

    /**
     * @param integer $index
     * @param WishlistItemInterface $itemToChange
     */
    private function decreaseExistingItem($index, WishlistItemInterface $itemToChange)
    {
        $existingItems = $this->wishlist->getItems();
        $existingItem = $existingItems[$index];
        $newQuantity = $existingItem->getQuantity() - $itemToChange->getQuantity();

        if ($newQuantity > 0) {
            $existingItem->setQuantity($newQuantity);
        } else {
            unset($existingItems[$index]);
        }
    }
}
