<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Wishlist\Service\Zed;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;

interface WishlistStubInterface
{
    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function addItem(WishlistChangeInterface $wishlistChange);

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function removeItem(WishlistChangeInterface $wishlistChange);

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function descreaseQuantity(WishlistChangeInterface $wishlistChange);

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function increaseQuantity(WishlistChangeInterface $wishlistChange);

    /**
     * @param CustomerInterface $customer
     *
     * @return WishlistInterface
     */
    public function getCustomerWishlist(CustomerInterface $customer);
}
