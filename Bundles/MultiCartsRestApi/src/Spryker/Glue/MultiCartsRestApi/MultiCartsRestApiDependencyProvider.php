<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiCartsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToMultiCartClientBridge;
use Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToPersistentCartClientBridge;

/**
 * @method \Spryker\Glue\MultiCartsRestApi\MultiCartsRestApiConfig getConfig()
 */
class MultiCartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const MULTI_CLIENT_CART = 'MULTI_CLIENT_CART';
    public const CLIENT_PERSISTENT_CART = 'CLIENT_PERSISTENT_CART';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addMultiCartClient($container);
        $container = $this->addPersistentCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addMultiCartClient(Container $container): Container
    {
        $container[static::MULTI_CLIENT_CART] = function (Container $container) {
            return new MultiCartsRestApiToMultiCartClientBridge($container->getLocator()->multiCart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addPersistentCartClient(Container $container): Container
    {
        $container[static::CLIENT_PERSISTENT_CART] = function (Container $container) {
            return new MultiCartsRestApiToPersistentCartClientBridge($container->getLocator()->persistentCart()->client());
        };

        return $container;
    }
}
