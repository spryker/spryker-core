<?php

namespace SprykerFeature\Client\Wishlist\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
use Generated\Shared\Transfer\WishlistTransfer;

class WishlistClient extends AbstractClient
{

    public function getWishlist()
    {
        return new WishlistTransfer();
    }

}
