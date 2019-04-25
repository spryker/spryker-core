<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig getConfig()
 */
class SharedCartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SHARED_CARTS_REST_API = 'CLIENT_SHARED_CARTS_REST_API';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addSharedCartsRestApiClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function addSharedCartsRestApiClient(Container $container): Container
    {
        $container[static::CLIENT_SHARED_CARTS_REST_API] = function (Container $container) {
            return $container->getLocator()->sharedCartsRestApi()->client();
        };

        return $container;
    }
}
