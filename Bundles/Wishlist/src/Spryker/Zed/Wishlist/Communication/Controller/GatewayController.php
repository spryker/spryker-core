<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Wishlist\Business\WishlistFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $changeTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function addItemAction(WishlistChangeTransfer $changeTransfer)
    {
        return $this->getFacade()->addItem($changeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $changeTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeItemAction(WishlistChangeTransfer $changeTransfer)
    {
        return $this->getFacade()->removeItem($changeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $changeTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function decreaseQuantityAction(WishlistChangeTransfer $changeTransfer)
    {
        return $this->getFacade()->decreaseQuantity($changeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $changeTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function increaseQuantityAction(WishlistChangeTransfer $changeTransfer)
    {
        return $this->getFacade()->increaseQuantity($changeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getCustomerWishlistAction(CustomerTransfer $customer)
    {
        return $this->getFacade()->getCustomerWishlist($customer);
    }

}
