<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Wishlist\Zed;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

interface WishlistStubInterface
{

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function addItem(WishlistChangeTransfer $wishlistChange);

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function removeItem(WishlistChangeTransfer $wishlistChange);

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function descreaseQuantity(WishlistChangeTransfer $wishlistChange);

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function increaseQuantity(WishlistChangeTransfer $wishlistChange);

    /**
     * @param CustomerTransfer $customer
     *
     * @return WishlistTransfer
     */
    public function getCustomerWishlist(CustomerTransfer $customer);

}
