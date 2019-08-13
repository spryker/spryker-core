<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence\Mapper;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;

class WishlistMapper implements WishlistMapperInterface
{
    /**
     * @param array $wishlist
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function mapWishlistEntityToWishlistTransfer(array $wishlist): WishlistTransfer
    {
        $wishlistTransfer = (new WishlistTransfer())
            ->fromArray($wishlist, true);

        return $wishlistTransfer;
    }

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlist $wishlistEntity
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function mapWishlistEntityToWishlistTransferWithItems(
        SpyWishlist $wishlistEntity,
        WishlistTransfer $wishlistTransfer
    ): WishlistTransfer {
        $wishlistTransfer = $wishlistTransfer->fromArray($wishlistEntity->toArray(), false);

        foreach ($wishlistEntity->getSpyWishlistItems() as $wishlistItemEntity) {
            $wishlistTransfer->addWishlistItem(
                $this->mapWishlistItemEntityToWishlistItemTransfer($wishlistItemEntity, new WishlistItemTransfer())
            );
        }
        $wishlistTransfer->setNumberOfItems($wishlistTransfer->getWishlistItems()->count());

        return $wishlistTransfer;
    }

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlistItem $wishlistItemEntity
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function mapWishlistItemEntityToWishlistItemTransfer(
        SpyWishlistItem $wishlistItemEntity,
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistItemTransfer {
        $wishlistItemTransfer = $wishlistItemTransfer->fromArray(
            $wishlistItemEntity->toArray(),
            true
        );

        return $wishlistItemTransfer;
    }
}
