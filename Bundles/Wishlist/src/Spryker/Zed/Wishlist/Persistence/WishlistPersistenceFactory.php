<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

use Orm\Zed\Wishlist\Persistence\SpyWishlistQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Wishlist\WishlistConfig getConfig()
 * @method \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainer getQueryContainer()
 */
class WishlistPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    public function createWishlistQuery()
    {
        return SpyWishlistQuery::create();
    }

}
