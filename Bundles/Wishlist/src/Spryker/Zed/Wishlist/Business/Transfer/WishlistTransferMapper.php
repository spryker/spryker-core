<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Transfer;

use Generated\Shared\Transfer\WishlistItemMetaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;
use Propel\Runtime\Collection\ObjectCollection;

class WishlistTransferMapper implements WishlistTransferMapperInterface
{
    /**
     * @var \Spryker\Zed\Wishlist\Dependency\Plugin\ItemExpanderPluginInterface[]
     */
    protected $itemExpanderPlugins;

    /**
     * @param \Spryker\Zed\Wishlist\Dependency\Plugin\ItemExpanderPluginInterface[] $itemExpanderPlugins
     */
    public function __construct(array $itemExpanderPlugins)
    {
        $this->itemExpanderPlugins = $itemExpanderPlugins;
    }

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlist $wishlistEntity
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function convertWishlist(SpyWishlist $wishlistEntity)
    {
        $wishlistTransfer = (new WishlistTransfer())
            ->fromArray($wishlistEntity->toArray(), true)
            ->setNumberOfItems($wishlistEntity->getSpyWishlistItems()->count());

        return $wishlistTransfer;
    }

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlist[]|\Propel\Runtime\Collection\ObjectCollection $wishlistEntityCollection
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer[]
     */
    public function convertWishlistCollection(ObjectCollection $wishlistEntityCollection)
    {
        $transferCollection = [];
        foreach ($wishlistEntityCollection as $wishlistEntity) {
            $transferCollection[] = $this->convertWishlist($wishlistEntity);
        }

        return $transferCollection;
    }

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlistItem $wishlistItemEntity
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function convertWishlistItem(SpyWishlistItem $wishlistItemEntity)
    {
        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->fromArray($wishlistItemEntity->toArray(), true);

        foreach ($this->itemExpanderPlugins as $plugin) {
            $wishlistItemTransfer = $plugin->expandItem($wishlistItemTransfer);
        }

        return $wishlistItemTransfer;
    }

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlistItem[]|\Propel\Runtime\Collection\ObjectCollection $wishlistItemEntityCollection
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer[]
     */
    public function convertWishlistItemCollection(ObjectCollection $wishlistItemEntityCollection)
    {
        $transferCollection = [];
        foreach ($wishlistItemEntityCollection as $wishlistEntity) {
            $transferCollection[] = $this->convertWishlistItem($wishlistEntity);
        }

        return $transferCollection;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param \Generated\Shared\Transfer\WishlistItemMetaTransfer $wishlistItemMetaTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemMetaTransfer
     */
    public function mapProductEntityToWishlistItemMetaTransfer(
        SpyProduct $productEntity,
        WishlistItemMetaTransfer $wishlistItemMetaTransfer
    ): WishlistItemMetaTransfer {
        return $wishlistItemMetaTransfer->fromArray($productEntity->toArray(), true);
    }
}
