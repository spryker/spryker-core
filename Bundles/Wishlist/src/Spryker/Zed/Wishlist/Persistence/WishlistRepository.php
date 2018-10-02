<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

use Generated\Shared\Transfer\WishlistCollectionTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
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
            ->joinWithSpyCustomer()
            ->where(SpyCustomerTableMap::COL_CUSTOMER_REFERENCE . ' = ?', $customerReference)
            ->find();

        foreach ($wishlistEntities as $wishlistEntity) {
            $wishlistTransfer = $this->getFactory()->createWishlistMapper()->mapWishlistEntityToWishlistTransfer($wishlistEntity->toArray());
            $wishlistCollection->addWishlist($wishlistTransfer);
        }

        return $wishlistCollection;
    }
}
