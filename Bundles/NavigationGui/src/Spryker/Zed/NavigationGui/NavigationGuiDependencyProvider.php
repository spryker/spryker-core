<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationBridge;
use Spryker\Zed\NavigationGui\Dependency\QueryContainer\NavigationGuiToNavigationBridge as NavigationGuiToNavigationQueryContainerBridge;

class NavigationGuiDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_NAVIGATION = 'FACADE_NAVIGATION';
    const QUERY_CONTAINER_NAVIGATION = 'QUERY_CONTAINER_NAVIGATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->provideNavigationFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $this->provideNavigationQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideNavigationFacade(Container $container)
    {
        $container[self::FACADE_NAVIGATION] = function (Container $container) {
            return new NavigationGuiToNavigationBridge($container->getLocator()->navigation()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideNavigationQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_NAVIGATION] = function (Container $container) {
            return new NavigationGuiToNavigationQueryContainerBridge($container->getLocator()->navigation()->queryContainer());
        };
    }

}
