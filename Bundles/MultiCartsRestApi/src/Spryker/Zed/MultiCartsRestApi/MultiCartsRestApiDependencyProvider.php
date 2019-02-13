<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCartsRestApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MultiCartsRestApi\Dependency\Facade\MultiCartsRestApiToMultiCartFacadeBridge;
use Spryker\Zed\MultiCartsRestApi\Dependency\Facade\MultiCartsRestApiToPersistentCartFacadeBridge;
use Spryker\Zed\MultiCartsRestApi\Dependency\Facade\MultiCartsRestApiToStoreFacadeBridge;

/**
 * @method \Spryker\Zed\MultiCartsRestApi\MultiCartsRestApiConfig getConfig()
 */
class MultiCartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_MULTI_CART = 'FACADE_MULTI_CART';
    public const FACADE_PERSISTENT_CART = 'FACADE_PERSISTENT_CART';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addMultiCartFacade($container);
        $container = $this->addPersistentCartFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new MultiCartsRestApiToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMultiCartFacade(Container $container): Container
    {
        $container[static::FACADE_MULTI_CART] = function (Container $container) {
            return new MultiCartsRestApiToMultiCartFacadeBridge($container->getLocator()->multiCart()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPersistentCartFacade(Container $container): Container
    {
        $container[static::FACADE_PERSISTENT_CART] = function (Container $container) {
            return new MultiCartsRestApiToPersistentCartFacadeBridge($container->getLocator()->persistentCart()->facade());
        };

        return $container;
    }
}
