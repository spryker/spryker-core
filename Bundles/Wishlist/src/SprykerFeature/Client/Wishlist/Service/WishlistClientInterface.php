<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Wishlist\Service;

use Generated\Shared\Wishlist\ItemInterface;
use Generated\Shared\Wishlist\WishlistInterface;


/**
 * @method WishlistDependencyContainer getDependencyContainer()
 */
interface WishlistClientInterface
{
    /**
     * @param ItemInterface $wishlistItem
     *
     * @return WishlistInterface
     */
    public function addItem(ItemInterface $wishlistItem);

    /**
     * @param ItemInterface $wishlistItem
     *
     * @return WishlistInterface
     */
    public function increaseItemQuantity(ItemInterface $wishlistItem);

    /**
     * @param ItemInterface $wishlistItem
     *
     * @return WishlistInterface
     */
    public function decreaseItemQuantity(ItemInterface $wishlistItem);

    /**
     * @param ItemInterface $wishlistItem
     *
     * @return WishlistInterface
     */
    public function removeItem(ItemInterface $wishlistItem);

    /**
     * @return WishlistInterface
     */
    public function getCustomerWishlist();

    /**
     * @return WishlistInterface
     */
    public function synchronizeSession();
}
