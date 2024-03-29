<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\WishlistsRestApi;

use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\WishlistsRestApi\WishlistsRestApiFactory getFactory()
 */
class WishlistsRestApiClient extends AbstractClient implements WishlistsRestApiClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function updateWishlist(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer
    {
        return $this->getFactory()->createZedStub()->updateWishlist($wishlistRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistFilterTransfer $wishlistFilterTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function deleteWishlist(WishlistFilterTransfer $wishlistFilterTransfer): WishlistResponseTransfer
    {
        return $this->getFactory()->createZedStub()->deleteWishlist($wishlistFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function addWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        return $this->getFactory()->createZedStub()->addWishlistItem($wishlistItemRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function deleteWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        return $this->getFactory()->createZedStub()->deleteWishlistItem($wishlistItemRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function updateWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        return $this->getFactory()->createZedStub()->updateWishlistItem($wishlistItemRequestTransfer);
    }
}
