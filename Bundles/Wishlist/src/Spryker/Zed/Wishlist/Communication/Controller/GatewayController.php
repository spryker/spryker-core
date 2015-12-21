<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Spryker\Zed\Wishlist\Business\WishlistFacade;

/**
 * @method WishlistFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param WishlistChangeTransfer $changeTransfer
     *
     * @return WishlistTransfer
     */
    public function addItemAction(WishlistChangeTransfer $changeTransfer)
    {
        return $this->getFacade()->addItem($changeTransfer);
    }

    /**
     * @param WishlistChangeTransfer $changeTransfer
     *
     * @return WishlistTransfer
     */
    public function removeItemAction(WishlistChangeTransfer $changeTransfer)
    {
        return $this->getFacade()->removeItem($changeTransfer);
    }

    /**
     * @param WishlistChangeTransfer $changeTransfer
     *
     * @return WishlistTransfer
     */
    public function decreaseQuantityAction(WishlistChangeTransfer $changeTransfer)
    {
        return $this->getFacade()->decreaseQuantity($changeTransfer);
    }

    /**
     * @param WishlistChangeTransfer $changeTransfer
     *
     * @return WishlistTransfer
     */
    public function increaseQuantityAction(WishlistChangeTransfer $changeTransfer)
    {
        return $this->getFacade()->increaseQuantity($changeTransfer);
    }

    /**
     * @param CustomerTransfer $customer
     *
     * @return WishlistTransfer
     */
    public function getCustomerWishlistAction(CustomerTransfer $customer)
    {
        return $this->getFacade()->getCustomerWishlist($customer);
    }

}
