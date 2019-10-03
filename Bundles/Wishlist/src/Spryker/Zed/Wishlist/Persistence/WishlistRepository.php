<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

use Generated\Shared\Transfer\WishlistCollectionTransfer;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Wishlist\Persistence\Map\SpyWishlistItemTableMap;
use Orm\Zed\Wishlist\Persistence\SpyWishlistQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Wishlist\Persistence\WishlistPersistenceFactory getFactory()
 */
class WishlistRepository extends AbstractRepository implements WishlistRepositoryInterface
{
    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\WishlistCollectionTransfer
     */
    public function getByCustomerReference(string $customerReference): WishlistCollectionTransfer
    {
        $wishlistEntities = $this->getFactory()->createWishlistQuery()
            ->useSpyCustomerQuery()
                ->filterByCustomerReference($customerReference)
            ->endUse()
            ->leftJoinWithSpyWishlistItem()
            ->find();

        if (!$wishlistEntities->count()) {
            return new WishlistCollectionTransfer();
        }

        return $this->getFactory()->createWishlistMapper()
            ->mapWishlistEntitiesToWishlistCollectionTransfer($wishlistEntities, new WishlistCollectionTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistFilterTransfer $wishlistFilterTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer|null
     */
    public function findWishlistByFilter(WishlistFilterTransfer $wishlistFilterTransfer): ?WishlistTransfer
    {
        $wishlistQuery = $this->getFactory()
            ->createWishlistQuery()
            ->leftJoinWithSpyWishlistItem()
            ->useSpyWishlistItemQuery(null, Criteria::LEFT_JOIN)
                ->withColumn(
                    sprintf('COUNT(%s)', SpyWishlistItemTableMap::COL_ID_WISHLIST_ITEM),
                    WishlistTransfer::NUMBER_OF_ITEMS
                )
                ->groupByFkWishlist()
            ->endUse();

        $wishlistEntityCollection = $this->applyFilters($wishlistQuery, $wishlistFilterTransfer)
            ->find();

        if (!$wishlistEntityCollection->count()) {
            return null;
        }

        return $this->getFactory()
            ->createWishlistMapper()
            ->mapWishlistEntityToWishlistTransfer($wishlistEntityCollection->getFirst(), new WishlistTransfer());
    }

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery $wishlistQuery
     * @param \Generated\Shared\Transfer\WishlistFilterTransfer $wishlistFilterTransfer
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    protected function applyFilters(SpyWishlistQuery $wishlistQuery, WishlistFilterTransfer $wishlistFilterTransfer): SpyWishlistQuery
    {
        if ($wishlistFilterTransfer->getIdCustomer()) {
            $wishlistQuery->filterByFkCustomer($wishlistFilterTransfer->getIdCustomer());
        }
        if ($wishlistFilterTransfer->getName()) {
            $wishlistQuery->filterByName($wishlistFilterTransfer->getName());
        }
        if ($wishlistFilterTransfer->getUuid()) {
            $wishlistQuery->filterByUuid($wishlistFilterTransfer->getUuid());
        }

        return $wishlistQuery;
    }
}
