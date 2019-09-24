<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business;

use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\WishlistsRestApi\Business\WishlistsRestApiBusinessFactory getFactory()
 * @method \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiEntityManagerInterface getEntityManager()
 */
class WishlistsRestApiFacade extends AbstractFacade implements WishlistsRestApiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return void
     */
    public function updateWishlistsUuid(): void
    {
        $this->getFactory()
            ->createWishlistUuidWriter()
            ->updateWishlistsUuid();
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
        return $this->getFactory()->createWishlistUpdater()->updateWishlist($wishlistRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistFilterTransfer $wishlistFilterTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function deleteWishlist(WishlistFilterTransfer $wishlistFilterTransfer): WishlistResponseTransfer
    {
        return $this->getFactory()->createWishlistDeleter()->deleteWishlist($wishlistFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function addWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        return $this->getFactory()->createWishlistItemAdder()->addWishlistItem($wishlistItemRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function deleteWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        return $this->getFactory()->createWishlistItemDeleter()->delete($wishlistItemRequestTransfer);
    }
}
