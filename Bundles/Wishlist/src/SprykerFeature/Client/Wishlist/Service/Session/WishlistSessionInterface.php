<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Wishlist\Service\Session;

use Generated\Shared\Wishlist\WishlistInterface;

interface WishlistSessionInterface
{
    /**
     * @return WishlistInterface
     */
    public function getWishlist();

    /**
     * @param WishlistInterface $wishlist
     *
     * @return $this
     */
    public function setWishlist(WishlistInterface $wishlist);
}
