<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientBridge;

class WishlistsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_WISHLIST = 'CLIENT_WISHLIST';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addWishlistClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addWishlistClient(Container $container): Container
    {
        $container[static::CLIENT_WISHLIST] = function (Container $container) {
            return new WishlistsRestApiToWishlistClientBridge($container->getLocator()->wishlist()->client());
        };

        return $container;
    }
}
