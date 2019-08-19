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
        $wishlistCollection = new WishlistCollectionTransfer();

        $wishlistEntities = $this->getFactory()->createWishlistQuery()
            ->useSpyCustomerQuery()
                ->filterByCustomerReference($customerReference)
            ->endUse()
            ->useSpyWishlistItemQuery(null, Criteria::LEFT_JOIN)
                ->withColumn(
                    sprintf('COUNT(%s)', SpyWishlistItemTableMap::COL_ID_WISHLIST_ITEM),
                    WishlistTransfer::NUMBER_OF_ITEMS
                )
                ->groupByFkWishlist()
            ->endUse()
            ->find();

        foreach ($wishlistEntities as $wishlistEntity) {
            $wishlistTransfer = $this->getFactory()->createWishlistMapper()->mapWishlistEntityToWishlistTransfer($wishlistEntity->toArray());
            $wishlistCollection->addWishlist($wishlistTransfer);
        }

        return $wishlistCollection;
    }

    /**
     * @api
     *
     * @param int $idCustomer
     * @param string $uuidWishlist
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer|null
     */
    public function getWishlistByCustomerIdAndUuid(int $idCustomer, string $uuidWishlist): ?WishlistTransfer
    {
        $wishlistEntity = $this->getFactory()
            ->createWishlistQuery()
            ->filterByFkCustomer($idCustomer)
            ->filterByUuid($uuidWishlist)
            ->leftJoinSpyWishlistItem()
            ->findOne();

        if (!$wishlistEntity) {
            return null;
        }

        return $this->getFactory()
            ->createWishlistMapper()
            ->mapWishlistEntityToWishlistTransferWithItems($wishlistEntity, new WishlistTransfer());
    }
}
