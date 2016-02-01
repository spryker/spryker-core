<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Client\Wishlist\Session;

use Generated\Shared\Transfer\WishlistTransfer;

interface WishlistSessionInterface
{

    /**
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlist();

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlist
     *
     * @return self
     */
    public function setWishlist(WishlistTransfer $wishlist);

}
