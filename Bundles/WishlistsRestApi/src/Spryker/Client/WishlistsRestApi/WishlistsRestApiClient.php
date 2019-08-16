<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\WishlistsRestApi;

use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\WishlistsRestApi\WishlistsRestApiFactory getFactory()
 */
class WishlistsRestApiClient extends AbstractClient implements WishlistsRestApiClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function getWishlistByUuid(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer
    {
        return $this->getFactory()->createZedStub()->getWishlistByUuid($wishlistRequestTransfer);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function deleteWishlist(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer
    {
        return $this->getFactory()->createZedStub()->deleteWishlist($wishlistRequestTransfer);
    }
}
