<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Wishlist\Persistence\WishlistPersistenceFactory getFactory()
 */
class WishlistEntityManager extends AbstractEntityManager implements WishlistEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function addItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        $wishlistItemTransfer->requireSku()
            ->requireFkWishlist();

        $wishlistItemEntity = $this->getFactory()
            ->createWishlistMapper()
            ->mapWishlistItemTransferToWishlistItemEntity($wishlistItemTransfer, new SpyWishlistItem());

        $wishlistItemEntity->save();

        return $this->getFactory()
            ->createWishlistMapper()
            ->mapWishlistItemEntityToWishlistItemTransfer($wishlistItemEntity, $wishlistItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return void
     */
    public function deleteItem(WishlistItemTransfer $wishlistItemTransfer): void
    {
        $wishlistItemTransfer->requireIdWishlistItem();

        $this->getFactory()
            ->createWishlistItemQuery()
            ->filterByIdWishlistItem($wishlistItemTransfer->getIdWishlistItemOrFail())
            ->delete();
    }
}
