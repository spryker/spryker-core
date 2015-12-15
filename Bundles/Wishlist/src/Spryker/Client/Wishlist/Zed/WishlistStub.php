<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Wishlist\Zed;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class WishlistStub implements WishlistStubInterface
{

    /**
     * @var ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function addItem(WishlistChangeTransfer $wishlistChange)
    {
        return $this->zedStub->call('/wishlist/gateway/add-item', $wishlistChange);
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function removeItem(WishlistChangeTransfer $wishlistChange)
    {
        return $this->zedStub->call('/wishlist/gateway/remove-item', $wishlistChange);
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function descreaseQuantity(WishlistChangeTransfer $wishlistChange)
    {
        return $this->zedStub->call('/wishlist/gateway/decrease-quantity', $wishlistChange);
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function increaseQuantity(WishlistChangeTransfer $wishlistChange)
    {
        return $this->zedStub->call('/wishlist/gateway/increase-quantity', $wishlistChange);
    }

    /**
     * @param CustomerTransfer $customer
     *
     * @return WishlistTransfer
     */
    public function getCustomerWishlist(CustomerTransfer $customer)
    {
        return $this->zedStub->call('/wishlist/gateway/get-customer-wishlist', $customer);
    }

}
