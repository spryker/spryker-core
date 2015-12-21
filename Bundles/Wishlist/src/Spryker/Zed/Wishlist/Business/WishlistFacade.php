<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method WishlistBusinessFactory getFactory()
 */
class WishlistFacade extends AbstractFacade
{

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function addItem(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createAddOperator($wishlistChange)->executeOperation();
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function removeItem(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createRemoveOperator($wishlistChange)->executeOperation();
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function decreaseQuantity(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createDecreaseOperator($wishlistChange)->executeOperation();
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return WishlistTransfer
     */
    public function increaseQuantity(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createIncreaseOperator($wishlistChange)->executeOperation();
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return WishlistTransfer
     */
    public function getCustomerWishlist(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()->createCustomer($customerTransfer)->getWishlist();
    }

}
