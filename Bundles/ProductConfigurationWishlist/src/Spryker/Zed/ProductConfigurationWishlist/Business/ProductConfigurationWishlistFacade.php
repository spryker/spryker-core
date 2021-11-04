<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlist\Business;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductConfigurationWishlist\Business\ProductConfigurationWishlistBusinessFactory getFactory()
 */
class ProductConfigurationWishlistFacade extends AbstractFacade implements ProductConfigurationWishlistFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function checkWishlistItemProductConfiguration(WishlistItemTransfer $wishlistItemTransfer): WishlistPreAddItemCheckResponseTransfer
    {
        return $this->getFactory()
            ->createProductConfigurationChecker()
            ->checkWishlistItemProductConfiguration($wishlistItemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function checkUpdateWishlistItemProductConfiguration(WishlistItemTransfer $wishlistItemTransfer): WishlistPreUpdateItemCheckResponseTransfer
    {
        return $this->getFactory()
            ->createProductConfigurationChecker()
            ->checkUpdateWishlistItemProductConfiguration($wishlistItemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemWithProductConfigurationData(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        return $this->getFactory()
            ->createProductConfigurationWishlistItemExpander()
            ->expandWishlistItemWithProductConfigurationData($wishlistItemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemWithProductConfiguration(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        return $this->getFactory()
            ->createProductConfigurationWishlistItemExpander()
            ->expandWishlistItemWithProductConfiguration($wishlistItemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function expandWishlistItemCollectionWithProductConfiguration(WishlistTransfer $wishlistTransfer): WishlistTransfer
    {
        return $this->getFactory()
            ->createProductConfigurationWishlistItemExpander()
            ->expandWishlistItemCollectionWithProductConfiguration($wishlistTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return bool
     */
    public function hasConfigurableProductItems(WishlistTransfer $wishlistTransfer): bool
    {
        return $this->getFactory()
            ->createProductConfigurationWishlistChecker()
            ->hasConfigurableProductItems($wishlistTransfer);
    }
}
