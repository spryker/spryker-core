<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery;
use Orm\Zed\Wishlist\Persistence\SpyWishlistQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Wishlist\Persistence\Mapper\WishlistMapper;
use Spryker\Zed\Wishlist\Persistence\Mapper\WishlistMapperInterface;

/**
 * @method \Spryker\Zed\Wishlist\WishlistConfig getConfig()
 * @method \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Wishlist\Persistence\WishlistRepositoryInterface getRepository()
 * @method \Spryker\Zed\Wishlist\Persistence\WishlistEntityManagerInterface getEntityManager()
 */
class WishlistPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @phpstan-return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery<\Orm\Zed\Wishlist\Persistence\SpyWishlist>
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    public function createWishlistQuery()
    {
        return SpyWishlistQuery::create();
    }

    /**
     * @phpstan-return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery<\Orm\Zed\Wishlist\Persistence\SpyWishlistItem>
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    public function createWishlistItemQuery()
    {
        return SpyWishlistItemQuery::create();
    }

    /**
     * @phpstan-return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function createProductQuery()
    {
        return SpyProductQuery::create();
    }

    /**
     * @return \Spryker\Zed\Wishlist\Persistence\Mapper\WishlistMapperInterface
     */
    public function createWishlistMapper(): WishlistMapperInterface
    {
        return new WishlistMapper();
    }
}
