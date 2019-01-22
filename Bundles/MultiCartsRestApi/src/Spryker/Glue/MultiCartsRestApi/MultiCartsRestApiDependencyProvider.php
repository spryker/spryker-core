<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiCartsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToCartsRestApiClientBridge;
use Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToMultiCartClientBridge;

/**
 * @method \Spryker\Glue\MultiCartsRestApi\MultiCartsRestApiConfig getConfig()
 */
class MultiCartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_MULTI_CART = 'CLIENT_MULTI_CART';
    public const CLIENT_CARTS_REST_API = 'CLIENT_CARTS_REST_API';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addMultiCartClient($container);
        $container = $this->addCartsRestApiClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addMultiCartClient(Container $container): Container
    {
        $container[static::CLIENT_MULTI_CART] = function (Container $container) {
            return new MultiCartsRestApiToMultiCartClientBridge($container->getLocator()->multiCart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCartsRestApiClient(Container $container): Container
    {
        $container[static::CLIENT_CARTS_REST_API] = function (Container $container) {
            return new MultiCartsRestApiToCartsRestApiClientBridge($container->getLocator()->cartsRestApi()->client());
        };

        return $container;
    }
}
