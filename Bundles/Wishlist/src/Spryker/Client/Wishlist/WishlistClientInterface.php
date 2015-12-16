<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Client\Wishlist;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

/**
 * @method WishlistDependencyContainer getFactory()
 */
interface WishlistClientInterface
{

    /**
     * @param ItemTransfer $wishlistItem
     *
     * @return WishlistTransfer
     */
    public function addItem(ItemTransfer $wishlistItem);

    /**
     * @param ItemTransfer $wishlistItem
     *
     * @return WishlistTransfer
     */
    public function increaseItemQuantity(ItemTransfer $wishlistItem);

    /**
     * @param ItemTransfer $wishlistItem
     *
     * @return WishlistTransfer
     */
    public function decreaseItemQuantity(ItemTransfer $wishlistItem);

    /**
     * @param ItemTransfer $wishlistItem
     *
     * @return WishlistTransfer
     */
    public function removeItem(ItemTransfer $wishlistItem);

    /**
     * @return WishlistTransfer
     */
    public function getWishlist();

    /**
     * @return WishlistTransfer
     */
    public function synchronizeSession();

}
