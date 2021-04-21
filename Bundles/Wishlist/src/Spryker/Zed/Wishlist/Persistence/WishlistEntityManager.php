<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

use Generated\Shared\Transfer\WishlistItemCriteriaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Wishlist\Business\Exception\MissingWishlistItemException;

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

        $wishlistItemQuery = $this->getFactory()->createWishlistItemQuery();
        $WishlistItemCriteriaTransfer = (new WishlistItemCriteriaTransfer())
            ->fromArray($wishlistItemTransfer->modifiedToArray(), true);

        $wishlistItemQuery->filterByArray(
            $WishlistItemCriteriaTransfer->modifiedToArrayNotRecursiveCamelCased()
        );

        $existedWishlistItemEntity = $wishlistItemQuery->findOne();

        if ($existedWishlistItemEntity) {
            return $this->getFactory()
                ->createWishlistMapper()
                ->mapWishlistItemEntityToWishlistItemTransfer($existedWishlistItemEntity, $wishlistItemTransfer);
        }

        $wishlistItemEntity->save();

        return $this->getFactory()
            ->createWishlistMapper()
            ->mapWishlistItemEntityToWishlistItemTransfer($wishlistItemEntity, $wishlistItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function deleteItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        $wishlistItemQuery = $this->getFactory()
            ->createWishlistItemQuery();

        $wishlistItemQuery = $this->applyRemoveFilters($wishlistItemQuery, $wishlistItemTransfer);
        $wishlistItemQuery->delete();

        return $wishlistItemTransfer;
    }

    /**
     * @phpstan-param \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery<mixed> $wishlistItemQuery
     *
     * @phpstan-return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery<mixed>
     *
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery $wishlistItemQuery
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @throws \Spryker\Zed\Wishlist\Business\Exception\MissingWishlistItemException
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    protected function applyRemoveFilters(
        SpyWishlistItemQuery $wishlistItemQuery,
        WishlistItemTransfer $wishlistItemTransfer
    ): SpyWishlistItemQuery {
        if (!$wishlistItemTransfer->getIdWishlistItem() && !$wishlistItemTransfer->getSku()) {
            throw new MissingWishlistItemException('Missing property idWishlistItem or sku in provided WishlistItemTransfer');
        }

        if ($wishlistItemTransfer->getIdWishlistItem()) {
            $wishlistItemQuery->filterByIdWishlistItem($wishlistItemTransfer->getIdWishlistItem());
        }

        if ($wishlistItemTransfer->getSku()) {
            $wishlistItemQuery->filterBySku($wishlistItemTransfer->getSku());
        }

        return $wishlistItemQuery;
    }
}
