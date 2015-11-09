<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Storage;

use Generated\Shared\Wishlist\ItemInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerFeature\Zed\Product\Business\ProductFacade;

class InMemory implements StorageInterface
{

    /**
     * @var ProductFacade
     */
    protected $facadeProduct;

    /**
     * @var WishlistInterface
     */
    protected $wishlist;

    /**
     * @param WishlistInterface $wishlist
     * @param ProductFacade $facadeProduct
     */
    public function __construct(WishlistInterface $wishlist, ProductFacade $facadeProduct)
    {
        $this->facadeProduct = $facadeProduct;
        $this->wishlist = $wishlist;
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
                $concreteProduct = $this->facadeProduct->getConcreteProduct($wishlistItem->getSku());
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
            if (isset($wishlistIndex[$wishlistItem->getGroupKey()])) {
                $this->decreaseItem($wishlistIndex[$wishlistItem->getGroupKey()], $wishlistItem);
            } else {
                $this->decreaseByProductIdentifier($wishlistIndex, $wishlistItem);
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
     * @param array $wishlistIndex
     * @param ItemInterface $itemToChange
     */
    protected function decreaseByProductIdentifier(array $wishlistIndex, ItemInterface $itemToChange)
    {
        foreach ($this->wishlist->getItems() as $key => $item) {
            if ($item->getSku() === $itemToChange->getSku()) {
                $this->decreaseItem($wishlistIndex[$item->getGroupKey()], $itemToChange);

                return;
            }
        }
    }

    /**
     * @param int $index
     * @param ItemInterface $itemToChange
     */
    protected function decreaseItem($index, ItemInterface $itemToChange)
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

    /**
     * @return array
     */
    protected function createIndex()
    {
        $wishlistItem = $this->wishlist->getItems();
        $wishlistIndex = [];
        foreach ($wishlistItem as $key => $cartItem) {
            if (!empty($cartItem->getGroupKey())) {
                $wishlistIndex[$cartItem->getGroupKey()] = $key;
            }
        }

        return $wishlistIndex;
    }

}
