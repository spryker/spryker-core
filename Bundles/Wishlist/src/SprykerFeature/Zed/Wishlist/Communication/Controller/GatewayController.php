<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Communication\Controller;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use SprykerFeature\Zed\Wishlist\Business\WishlistFacadeInterface;

/**
 * @method WishlistFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param WishlistChangeInterface $changeTransfer
     *
     * @return WishlistInterface
     */
    public function addItemAction(WishlistChangeInterface $changeTransfer)
    {
        return $this->getFacade()->addItem($changeTransfer);
    }

    /**
     * @param WishlistChangeInterface $changeTransfer
     *
     * @return WishlistInterface
     */
    public function removeItemAction(WishlistChangeInterface $changeTransfer)
    {
        return $this->getFacade()->removeItem($changeTransfer);
    }

    /**
     * @param WishlistChangeInterface $changeTransfer
     *
     * @return WishlistInterface
     */
    public function decreaseQuantityAction(WishlistChangeInterface $changeTransfer)
    {
        return $this->getFacade()->decreaseQuantity($changeTransfer);
    }

    /**
     * @param WishlistChangeInterface $changeTransfer
     *
     * @return WishlistInterface
     */
    public function increaseQuantityAction(WishlistChangeInterface $changeTransfer)
    {
        return $this->getFacade()->increaseQuantity($changeTransfer);
    }

    /**
     * @param CustomerInterface $customer
     *
     * @return WishlistInterface
     */
    public function getCustomerWishlistAction(CustomerInterface $customer)
    {
        return $this->getFacade()->getCustomerWishlist($customer);
    }

}
