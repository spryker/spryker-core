<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

use Generated\Shared\Transfer\WishlistCollectionTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Wishlist\Persistence\Map\SpyWishlistItemTableMap;
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
            ->useSpyWishlistItemQuery(null, Criteria::LEFT_JOIN)
                ->withColumn(
                    sprintf('COUNT(%s)', SpyWishlistItemTableMap::COL_ID_WISHLIST_ITEM),
                    WishlistTransfer::NUMBER_OF_ITEMS
                )
                ->groupByFkWishlist()
            ->endUse()
            ->find();

        if (!$wishlistEntities->count()) {
            return new WishlistCollectionTransfer();
        }

        return $this->getFactory()->createWishlistMapper()
            ->mapWishlistEntitiesToWishlistCollectionTransfer($wishlistEntities, new WishlistCollectionTransfer());
    }

    /**
     * @param int $idCustomer
     * @param string $uuidWishlist
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer|null
     */
    public function findCustomerWishlistByUuid(int $idCustomer, string $uuidWishlist): ?WishlistTransfer
    {
        $wishlistEntityCollection = $this->getFactory()
            ->createWishlistQuery()
            ->filterByFkCustomer($idCustomer)
            ->filterByUuid($uuidWishlist)
            ->leftJoinWithSpyWishlistItem()
            ->find();

        if (!$wishlistEntityCollection->count()) {
            return null;
        }

        return $this->getFactory()
            ->createWishlistMapper()
            ->mapWishlistEntityToWishlistTransfer($wishlistEntityCollection->getFirst(), new WishlistTransfer());
    }
}
