<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Wishlist\Persistence\WishlistPersistenceFactory getFactory()
 */
class WishlistQueryContainer extends AbstractQueryContainer implements WishlistQueryContainerInterface
{

    /**
     * @api
     *
     * @param int $idWishlist
     * @param int $idProduct
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    public function queryCustomerWishlistByProductId($idWishlist, $idProduct)
    {
        $query = $this->getFactory()->createWishlistItemQuery()
            ->filterByFkWishlist($idWishlist)
            ->filterByFkProduct($idProduct);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idWishlist
     * @param string $groupKey
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    public function queryCustomerWishlistByGroupKey($idWishlist, $groupKey)
    {
        $query = $this->getFactory()->createWishlistItemQuery()
            ->filterByFkWishlist($idWishlist)
            ->filterByGroupKey($groupKey);

        return $query;
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
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    public function queryWishlist()
    {
        return $this->getFactory()->createWishlistQuery();
    }

}
