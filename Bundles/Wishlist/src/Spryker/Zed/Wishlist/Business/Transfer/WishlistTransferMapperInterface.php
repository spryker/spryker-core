<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Transfer;


use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Propel\Runtime\Collection\ObjectCollection;

interface WishlistTransferMapperInterface
{

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlist $wishlistEntity
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function convertWishlistItem(SpyWishlist $wishlistEntity);

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlist[]|\Propel\Runtime\Collection\ObjectCollection $wishlistEntityCollection
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer[]
     */
    public function convertWishlistItemCollection(ObjectCollection $wishlistEntityCollection);

}
