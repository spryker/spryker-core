<?php

namespace SprykerFeature\Client\Wishlist\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;

class WishlistClient extends AbstractClient
{

    public function getWishlist()
    {
        $cart = $this->getDependencyContainer();

        return $cart;
    }

    public function addWishlistItem()
    {}
}
