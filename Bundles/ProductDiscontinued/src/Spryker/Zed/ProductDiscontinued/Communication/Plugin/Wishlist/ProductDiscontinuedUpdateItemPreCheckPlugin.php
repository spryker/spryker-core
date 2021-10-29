<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\UpdateItemPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinued\Communication\ProductDiscontinuedCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductDiscontinued\ProductDiscontinuedConfig getConfig()
 */
class ProductDiscontinuedUpdateItemPreCheckPlugin extends AbstractPlugin implements UpdateItemPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if wishlist item is not discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function check(WishlistItemTransfer $wishlistItemTransfer): WishlistPreUpdateItemCheckResponseTransfer
    {
        return $this->getFacade()->checkUpdateWishlistItemProductIsNotDiscontinued($wishlistItemTransfer);
    }
}
