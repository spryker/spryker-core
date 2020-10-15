<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartsRestApi;

use Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToCartsRestApiFacadeBridge;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToPersistentCartFacadeBridge;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Facade\ConfigurableBundleCartsRestApiToStoreFacadeBridge;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Service\ConfigurableBundleCartsRestApiToConfigurableBundleCartServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig getConfig()
 */
class ConfigurableBundleCartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PERSISTENT_CART = 'FACADE_PERSISTENT_CART';
    public const FACADE_CARTS_REST_API = 'FACADE_CARTS_REST_API';
    public const FACADE_STORE = 'FACADE_STORE';

    public const SERVICE_CONFIGURABLE_BUNDLE_CART = 'SERVICE_CONFIGURABLE_BUNDLE_CART';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addPersistentCartFacade($container);
        $container = $this->addCartsRestApiFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addConfigurableBundleCartService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPersistentCartFacade(Container $container): Container
    {
        $container->set(static::FACADE_PERSISTENT_CART, function (Container $container) {
            return new ConfigurableBundleCartsRestApiToPersistentCartFacadeBridge($container->getLocator()->persistentCart()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartsRestApiFacade(Container $container): Container
    {
        $container->set(static::FACADE_CARTS_REST_API, function (Container $container) {
            return new ConfigurableBundleCartsRestApiToCartsRestApiFacadeBridge($container->getLocator()->cartsRestApi()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new ConfigurableBundleCartsRestApiToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleCartService(Container $container): Container
    {
        $container->set(static::SERVICE_CONFIGURABLE_BUNDLE_CART, function (Container $container) {
            return new ConfigurableBundleCartsRestApiToConfigurableBundleCartServiceBridge($container->getLocator()->configurableBundleCart()->service());
        });

        return $container;
    }
}
