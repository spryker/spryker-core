<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Wishlist\Persistence\WishlistPersistenceFactory getFactory()
 */
class WishlistQueryContainer extends AbstractQueryContainer implements WishlistQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    public function queryWishlist()
    {
        return $this->getFactory()->createWishlistQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    public function queryWishlistItem()
    {
        return $this->getFactory()->createWishlistItemQuery();
    }

    /**
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    public function queryWishlistByCustomerId($idCustomer)
    {
        return $this->getFactory()
            ->createWishlistQuery()
            ->filterByFkCustomer($idCustomer)
            ->orderByName(Criteria::ASC);
    }

    /**
     * @api
     *
     * @param int $idWishlist
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    public function queryItemsByWishlistId($idWishlist)
    {
        return $this->getFactory()
            ->createWishlistItemQuery()
            ->filterByFkWishlist($idWishlist);
    }
}
