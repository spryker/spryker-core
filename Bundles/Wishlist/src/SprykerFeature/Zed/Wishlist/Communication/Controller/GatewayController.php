<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Communication\Controller;

use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use SprykerFeature\Zed\Wishlist\Business\WishlistFacade;

/**
 * @method WishlistFacade  getFacade()
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
    public function removeAction(WishlistChangeInterface $changeTransfer)
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
}
