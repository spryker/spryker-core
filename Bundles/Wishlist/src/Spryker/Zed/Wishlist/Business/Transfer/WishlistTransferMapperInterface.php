<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Transfer;

use ArrayObject;
use Generated\Shared\Transfer\WishlistItemMetaTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;
use Propel\Runtime\Collection\ObjectCollection;

interface WishlistTransferMapperInterface
{
    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlist $wishlistEntity
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function convertWishlist(SpyWishlist $wishlistEntity);

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlist[]|\Propel\Runtime\Collection\ObjectCollection $wishlistEntityCollection
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer[]
     */
    public function convertWishlistCollection(ObjectCollection $wishlistEntityCollection);

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlistItem $wishlistItemEntity
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function convertWishlistItem(SpyWishlistItem $wishlistItemEntity);

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlistItem[]|\Propel\Runtime\Collection\ObjectCollection $wishlistItemEntityCollection
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer[]
     */
    public function convertWishlistItemCollection(ObjectCollection $wishlistItemEntityCollection);

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param \Generated\Shared\Transfer\WishlistItemMetaTransfer $wishlistItemMetaTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemMetaTransfer
     */
    public function mapProductEntityToWishlistItemMetaTransfer(
        SpyProduct $productEntity,
        WishlistItemMetaTransfer $wishlistItemMetaTransfer
    ): WishlistItemMetaTransfer;

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemMetaTransfer> $wishlistItemMetaTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemMetaTransfer>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\WishlistItemTransfer[] $wishlistItemTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\WishlistItemMetaTransfer[] $wishlistItemMetaTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\WishlistItemMetaTransfer[]
     */
    public function mapWishlistItemTransfersToWishlistItemMetaTransfers(
        ArrayObject $wishlistItemTransfers,
        ArrayObject $wishlistItemMetaTransfers
    ): ArrayObject;
}
