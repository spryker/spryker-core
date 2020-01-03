<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi;

use Spryker\Glue\CartPermissionGroupsRestApi\Dependency\Client\CartPermissionGroupsRestApiToSharedCartClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CartPermissionGroupsRestApi\CartPermissionGroupsRestApiConfig getConfig()
 */
class CartPermissionGroupsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SHARED_CART = 'CLIENT_SHARED_CART';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addSharedCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addSharedCartClient(Container $container): Container
    {
        $container[static::CLIENT_SHARED_CART] = function (Container $container) {
            return new CartPermissionGroupsRestApiToSharedCartClientBridge(
                $container->getLocator()->sharedCart()->client()
            );
        };

        return $container;
    }
}
