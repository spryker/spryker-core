<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Wishlist\Service\Storage;

use Generated\Shared\Wishlist\WishlistInterface;

interface WishlistStorageInterface
{
    /**
     * @return mixed
     */
    public function expandProductDetails(WishlistInterface $wishlist);
}
