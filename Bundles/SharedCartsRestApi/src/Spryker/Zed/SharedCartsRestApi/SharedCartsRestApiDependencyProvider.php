<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeBridge;
use Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToStoreFacadeBridge;

/**
 * @method \Spryker\Zed\SharedCartsRestApi\SharedCartsRestApiConfig getConfig()
 */
class SharedCartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_SHARED_CART = 'FACADE_SHARED_CART';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addSharedCartFacade($container);
        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSharedCartFacade(Container $container): Container
    {
        $container[static::FACADE_SHARED_CART] = function (Container $container) {
            $sharedCartsRestApiToSharedCartFacadeBridge = new SharedCartsRestApiToSharedCartFacadeBridge(
                $container->getLocator()->sharedCart()->facade()
            );

            return $sharedCartsRestApiToSharedCartFacadeBridge;
        };

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
            $sharedCartsRestApiToStoreFacadeBridge = new SharedCartsRestApiToStoreFacadeBridge(
                $container->getLocator()->store()->facade()
            );

            return $sharedCartsRestApiToStoreFacadeBridge;
        };

        return $container;
    }
}
