<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Client\Wishlist\Zed;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;

interface WishlistStubInterface
{

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function addItem(WishlistChangeTransfer $wishlistChange);

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeItem(WishlistChangeTransfer $wishlistChange);

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function descreaseQuantity(WishlistChangeTransfer $wishlistChange);

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function increaseQuantity(WishlistChangeTransfer $wishlistChange);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getCustomerWishlist(CustomerTransfer $customer);

}
