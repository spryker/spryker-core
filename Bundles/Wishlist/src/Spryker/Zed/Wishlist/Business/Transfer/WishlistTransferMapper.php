<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Transfer;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Propel\Runtime\Collection\ObjectCollection;

class WishlistTransferMapper implements WishlistTransferMapperInterface
{

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlist $wishlistEntity
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function convertWishlistItem(SpyWishlist $wishlistEntity)
    {
        $wishlistTransfer = (new WishlistItemTransfer())
            ->fromArray($wishlistEntity->toArray(), true);

        return $wishlistTransfer;
    }

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlist[]|\Propel\Runtime\Collection\ObjectCollection $wishlistEntityCollection
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer[]
     */
    public function convertWishlistItemCollection(ObjectCollection $wishlistEntityCollection)
    {
        $transferList = [];
        foreach ($wishlistEntityCollection as $wishlistEntity) {
            $transferList[] = $this->convertWishlistItem($wishlistEntity);
        }

        return $transferList;
    }

}
