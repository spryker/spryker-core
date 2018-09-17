<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Persistence;

use Orm\Zed\Wishlist\Persistence\SpyWishlistQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\WishlistsRestApi\WishlistsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\WishlistsRestApi\WishlistsRestApiConfig getConfig()
 */
class WishlistsRestApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    public function getWishlistPropelQuery(): SpyWishlistQuery
    {
        return $this->getProvidedDependency(WishlistsRestApiDependencyProvider::PROPEL_QUERY_WISHLIST);
    }
}
