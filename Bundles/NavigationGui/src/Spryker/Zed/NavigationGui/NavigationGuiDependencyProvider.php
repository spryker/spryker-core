<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToLocaleBridge;
use Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationBridge;
use Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToUrlBridge;

class NavigationGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_NAVIGATION = 'FACADE_NAVIGATION';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_URL = 'FACADE_URL';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->provideNavigationFacade($container);
        $this->provideLocaleFacade($container);
        $this->provideUrlFacade($container);

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
    protected function provideLocaleFacade(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new NavigationGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideUrlFacade(Container $container)
    {
        $container[self::FACADE_URL] = function (Container $container) {
            return new NavigationGuiToUrlBridge($container->getLocator()->url()->facade());
        };
    }
}
