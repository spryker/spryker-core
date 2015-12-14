<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Wishlist\Storage;

use Generated\Shared\Transfer\WishlistTransfer;

interface WishlistStorageInterface
{

    /**
     * @return mixed
     */
    public function expandProductDetails(WishlistTransfer $wishlist);

}
