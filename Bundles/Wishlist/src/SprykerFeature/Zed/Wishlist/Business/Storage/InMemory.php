<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Storage;

use Generated\Shared\Wishlist\ItemInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerFeature\Zed\Product\Business\ProductFacade;

class InMemory extends BaseStorage implements StorageInterface
{
    /**
     * @var ProductFacade
     */
    protected $productFacade;

    /**
     * @param WishlistInterface $wishlist
     * @param ProductFacade     $productFacade
     */
    public function __construct(WishlistInterface $wishlist, ProductFacade $productFacade)
    {
        parent::__construct($wishlist);
        $this->productFacade = $productFacade;
    }
    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function addItems(WishlistChangeInterface $wishlistChange)
    {
        $wishlistIndex = $this->createIndex();
        foreach ($wishlistChange->getItems() as $wishlistItem) {

            if (isset($wishlistIndex[$wishlistItem->getGroupKey()])) {
                $key = $wishlistIndex[$wishlistItem->getGroupKey()];
                $existingItem = $this->wishlist->getItems()[$key];
                $existingItem->setQuantity($wishlistItem->getQuantity() + $existingItem->getQuantity());
            } else {
                $concreteProduct = $this->productFacade->getConcreteProduct($wishlistItem->getSku());
                $wishlistItem->setIdAbstractProduct($concreteProduct->getIdAbstractProduct());
                $this->wishlist->addItem($wishlistItem);
            }
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
        foreach ($wishlistChange->getItems() as $key => $wishlistItem) {
            $this->reduceTransferItem($wishlistIndex, $wishlistItem);
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
     * @param array $wishlistIndex
     * @param ItemInterface $wishlistItem
     */
    protected function reduceTransferItem(array $wishlistIndex, ItemInterface $wishlistItem)
    {
        if (isset($wishlistIndex[$wishlistItem->getGroupKey()])) {
            $this->decreaseExistingItem($wishlistIndex[$wishlistItem->getGroupKey()], $wishlistItem);
        } else {
            $this->decreaseByProductIdentifier($wishlistItem);
        }
    }

    /**
     * @param ItemInterface $wishlistItem
     */
    protected function decreaseByProductIdentifier(ItemInterface $wishlistItem)
    {
        foreach ($this->wishlist->getItems() as $key => $existingItem) {
            if ($existingItem->getSku() === $wishlistItem->getSku()) {
                return;
            }
        }
    }

    /**
     * @param integer $index
     * @param ItemInterface $itemToChange
     */
    protected function decreaseExistingItem($index, ItemInterface $itemToChange)
    {
        $existingItems = $this->wishlist->getItems();
        $existingItem = $existingItems[$index];
        $newQuantity = $existingItem->getQuantity() - $itemToChange->getQuantity();

        if ($newQuantity > 0 && $itemToChange->getQuantity() > 0) {
            $existingItem->setQuantity($newQuantity);
        } else {
            unset($existingItems[$index]);
        }
    }
}
