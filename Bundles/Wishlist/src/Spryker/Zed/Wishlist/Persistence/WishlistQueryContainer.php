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
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    public function queryWishlist()
    {
        return $this->getFactory()->createWishlistQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    public function queryWishlistItem()
    {
        return $this->getFactory()->createWishlistItemQuery();
    }

    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
