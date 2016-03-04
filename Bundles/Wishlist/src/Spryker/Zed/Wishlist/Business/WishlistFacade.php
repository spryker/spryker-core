<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Wishlist\Business\WishlistBusinessFactory getFactory()
 */
class WishlistFacade extends AbstractFacade implements WishlistFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function addItem(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createAddOperator($wishlistChange)->executeOperation();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeItem(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createRemoveOperator($wishlistChange)->executeOperation();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function decreaseQuantity(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createDecreaseOperator($wishlistChange)->executeOperation();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function increaseQuantity(WishlistChangeTransfer $wishlistChange)
    {
        return $this->getFactory()->createIncreaseOperator($wishlistChange)->executeOperation();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getCustomerWishlist(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()->createCustomer($customerTransfer)->getWishlist();
    }

}
