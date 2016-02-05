<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Persistence;

use Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery;
use Orm\Zed\Wishlist\Persistence\SpyWishlistQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Wishlist\WishlistConfig getConfig()
 * @method \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainer getQueryContainer()
 */
class WishlistPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    public function createWishlistItemQuery()
    {
        return SpyWishlistItemQuery::create();
    }

    /**
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    public function createWishlistQuery()
    {
        return SpyWishlistQuery::create();
    }

}
