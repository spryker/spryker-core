<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service\Zed;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;

class WishlistStub implements WishlistStubInterface
{
    /**
     * @var ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param ZedRequestClient $zedClientStub
     */
    public function __construct(ZedRequestClient $zedClientStub)
    {
        $this->zedStub = $zedClientStub;
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function addItem(WishlistChangeInterface $wishlistChange)
    {
        return $this->zedStub->call('/wishlist/gateway/add-item', $wishlistChange);
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function removeItem(WishlistChangeInterface $wishlistChange)
    {
        return $this->zedStub->call('/wishlist/gateway/remove-item', $wishlistChange);
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function descreaseQuantity(WishlistChangeInterface $wishlistChange)
    {
        return $this->zedStub->call('/wishlist/gateway/decrease-quantity', $wishlistChange);
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function increaseQuantity(WishlistChangeInterface $wishlistChange)
    {
        return $this->zedStub->call('/wishlist/gateway/increase-quantity', $wishlistChange);
    }

    /**
     * @param CustomerInterface $customer
     *
     * @return WishlistInterface
     */
    public function getCustomerWishlist(CustomerInterface $customer)
    {
        return $this->zedStub->call('/wishlist/gateway/get-customer-wishlist', $customer);
    }


}
