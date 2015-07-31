<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Storage;

use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItem;
use SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainer;

class Propel extends BaseStorage implements StorageInterface
{
    /**
     * @var WishlistQueryContainer
     */
    protected $wishlistQueryContainer;

    /**
     * @param WishlistQueryContainer  $wishlistQueryContainer
     * @param WishlistInterface $wishlist
     */
    public function __construct(
        WishlistQueryContainer $wishlistQueryContainer,
        WishlistInterface $wishlist
    ) {
        parent::__construct($this->wishlist);
        $this->wishlistQueryContainer = $wishlistQueryContainer;
    }

    public function createNewItem(WishlistChangeInterface $wishlistChange)
    {
        foreach ($wishlistChange->getItems() as $item) {
            $spyWishlistItem = $this->getWishlistItem($item);

            if (empty($spyWishlistItem)) {
                $this->createNewWishlistItem($item);
            } else {
                $spyWishlistItem->setQuantity($spyWishlistItem->getQuantity() + 1);
            }


        }

        return $wishlistChange;
    }

    public function increaseItems(WishlistChangeInterface $wishlistChange)
    {
        foreach ($wishlistChange->getItems() as $item) {
            $spyWishlistItem = $this->getWishlistItem($item);
            $spyWishlistItem->getQuantity($spyWishlistItem->getQuantity() + 1);
            $spyWishlistItem->save();
        }

        return $wishlistChange;
    }

    public function decreaseQuantity(WishlistChangeInterface $wishlistChange)
    {
        $wishlistItems = $wishlistChange->getItems();
        foreach ($wishlistItems as $key => $item) {
            $spyWishlistItem = $this->getWishlistItem($item);
            if ($spyWishlistItem->getQuantity() <= 1) {
                $spyWishlistItem->delete();
                unset($wishlistItems[$key]);
            } else {
                $spyWishlistItem->getQuantity($spyWishlistItem->getQuantity() - 1);
                $spyWishlistItem->save();
            }
        }

        return $wishlistChange;
    }

    public function decreaseItems(WishlistChangeInterface $wishlistChange)
    {
        $wishlistItems = $wishlistChange->getItems();
        foreach ($wishlistItems as $key => $item) {
            $spyWishlistItem = $this->getWishlistItem($item);
            $spyWishlistItem->delete();
            unset($wishlistItems[$key]);
        }

        return $wishlistChange;
    }


    /**
     * @param WishlistItemInterface $wishlistItem
     * @return SpyWishlistItem
     */
    protected function getWishlistItem(WishlistItemInterface $wishlistItem)
    {
        $spyWishlistItem = null;
        if (!empty($wishlistItem->getGroupKey())) {
            $spyWishlistItem = $this->wishlistQueryContainer
                ->getWishlistItemQuery()
                ->findOneByGroupKey($wishlistItem->getGroupKey());
        }

        if (empty($spyWishlistItem)) {
            $spyWishlistItem = $this->wishlistQueryContainer->getWishlistItemQuery()->find();
        }

        return $spyWishlistItem;
    }


    /**
     * @param WishlistChangeInterface $wishlistChange
     */
    public function removeItems(WishlistChangeInterface $wishlistChange)
    {
        // TODO: Implement removeItems() method.
    }

}
