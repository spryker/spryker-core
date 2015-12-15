<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Wishlist\Business\Storage;

use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

interface StorageInterface
{

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function addItems(WishlistChangeTransfer $wishlistChange);

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function removeItems(WishlistChangeTransfer $wishlistChange);

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function increaseItems(WishlistChangeTransfer $wishlistChange);

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function decreaseItems(WishlistChangeTransfer $wishlistChange);

}
