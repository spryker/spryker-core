<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlistsRestApi\Business;

use ArrayObject;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductConfigurationWishlistsRestApi\Business\ProductConfigurationWishlistsRestApiBusinessFactory getFactory()
 */
class ProductConfigurationWishlistsRestApiFacade extends AbstractFacade implements ProductConfigurationWishlistsRestApiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer|null
     */
    public function findWishlistItemByProductConfiguration(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): ?WishlistItemTransfer {
        return $this->getFactory()
            ->createWishlistItemReader()
            ->findWishlistItemByProductConfiguration($wishlistItemRequestTransfer, $wishlistItemTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return void
     */
    public function deleteWishlistItem(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): void {
        $this->getFactory()
            ->createWishlistItemDeleter()
            ->deleteWishlistItem($wishlistItemRequestTransfer, $wishlistItemTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function updateWishlistItem(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): WishlistItemResponseTransfer {
        return $this->getFactory()
            ->createWishlistItemUpdater()
            ->updateWishlistItem($wishlistItemRequestTransfer, $wishlistItemTransfers);
    }
}
