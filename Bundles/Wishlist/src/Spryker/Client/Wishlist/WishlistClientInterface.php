<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Client\Wishlist;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

/**
 * @method WishlistFactory getFactory()
 */
interface WishlistClientInterface
{

    /**
     * @param ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function addItem(ItemTransfer $wishlistItem);

    /**
     * @param ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function increaseItemQuantity(ItemTransfer $wishlistItem);

    /**
     * @param ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function decreaseItemQuantity(ItemTransfer $wishlistItem);

    /**
     * @param ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeItem(ItemTransfer $wishlistItem);

    /**
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlist();

    /**
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function synchronizeSession();

}
