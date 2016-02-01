<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Client\Wishlist;

use Generated\Shared\Transfer\ItemTransfer;

/**
 * @method WishlistFactory getFactory()
 */
interface WishlistClientInterface
{

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function addItem(ItemTransfer $wishlistItem);

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function increaseItemQuantity(ItemTransfer $wishlistItem);

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function decreaseItemQuantity(ItemTransfer $wishlistItem);

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItem
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
