<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Wishlist\Business\WishlistBusinessFactory getFactory()
 */
class WishlistFacade extends AbstractFacade
{

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function addItem(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createAddOperator($wishlistChange)->executeOperation();
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeItem(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createRemoveOperator($wishlistChange)->executeOperation();
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function decreaseQuantity(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createDecreaseOperator($wishlistChange)->executeOperation();
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function increaseQuantity(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createIncreaseOperator($wishlistChange)->executeOperation();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getCustomerWishlist(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()->createCustomer($customerTransfer)->getWishlist();
    }

}
