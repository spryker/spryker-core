<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi;

use Orm\Zed\Wishlist\Persistence\SpyWishlistQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeBridge;

/**
 * @method \Spryker\Zed\WishlistsRestApi\WishlistsRestApiConfig getConfig()
 */
class WishlistsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_WISHLIST = 'PROPEL_QUERY_WISHLIST';

    public const FACADE_WISHLIST = 'FACADE_WISHLIST';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addWishlistPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addWishlistFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addWishlistPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_WISHLIST] = function () {
            return SpyWishlistQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addWishlistFacade(Container $container): Container
    {
        $container->set(static::FACADE_WISHLIST, function (Container $container) {
            return new WishlistsRestApiToWishlistFacadeBridge(
                $container->getLocator()->wishlist()->facade()
            );
        });

        return $container;
    }
}
