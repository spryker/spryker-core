<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Client\Wishlist\Session;

use Generated\Shared\Transfer\WishlistTransfer;

interface WishlistSessionInterface
{

    /**
     * @return WishlistTransfer
     */
    public function getWishlist();

    /**
     * @param WishlistTransfer $wishlist
     *
     * @return self
     */
    public function setWishlist(WishlistTransfer $wishlist);

}
