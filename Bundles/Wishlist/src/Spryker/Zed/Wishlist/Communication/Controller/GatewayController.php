<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Communication\Controller;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Wishlist\Business\WishlistFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $WishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function addItemAction(WishlistItemTransfer $WishlistItemTransfer)
    {
        return $this->getFacade()->addItem($WishlistItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $WishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function removeItemAction(WishlistItemTransfer $WishlistItemTransfer)
    {
        return $this->getFacade()->removeItem($WishlistItemTransfer);
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getCustomerWishlistAction($idCustomer)
    {
        return $this->getFacade()->getCustomerWishlist($idCustomer);
    }

}
