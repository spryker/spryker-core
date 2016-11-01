<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Wishlist\WishlistFactory getFactory()
 */
class WishlistClient extends AbstractClient implements WishlistClientInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function addItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        return $this->getZedStub()->addItem($wishlistItemTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        return $this->getZedStub()->removeItem($wishlistItemTransfer);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlist($idCustomer)
    {
        return $this->getZedStub()->getCustomerWishlist($idCustomer);
    }

    /**
     * @return \Spryker\Client\Wishlist\Zed\WishlistStubInterface
     */
    protected function getZedStub()
    {
        return $this->getFactory()->createZedStub();
    }

}
