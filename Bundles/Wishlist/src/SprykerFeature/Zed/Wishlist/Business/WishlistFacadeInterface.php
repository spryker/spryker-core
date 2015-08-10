<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Wishlist\Business;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;


/**
 * @method WishlistDependencyContainer getDependencyContainer()
 */
interface WishlistFacadeInterface
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
    public function decreaseQuantity(WishlistChangeInterface $wishlistChange);

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function increaseQuantity(WishlistChangeInterface $wishlistChange);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return WishlistInterface
     */
    public function getCustomerWishlist(CustomerInterface $customerTransfer);
}
