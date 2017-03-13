<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Navigation\Dependency\Facade\NavigationToCollectorBridge;
use Spryker\Zed\Navigation\Dependency\Facade\NavigationToTouchBridge;

class NavigationDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_TOUCH = 'FACADE_TOUCH';
    const FACADE_COLLECTOR = 'FACADE_COLLECTOR';

    const QUERY_CONTAINER_TOUCH = 'QUERY_CONTAINER_TOUCH';

    const SERVICE_DATA_READER = 'SERVICE_DATA_READER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->provideTouchFacade($container);
        $this->provideCollectorFacade($container);
        $this->provideTouchQueryContainer($container);
        $this->provideDataReaderService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideTouchFacade(Container $container)
    {
        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new NavigationToTouchBridge($container->getLocator()->touch()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideCollectorFacade(Container $container)
    {
        $container[self::FACADE_COLLECTOR] = function (Container $container) {
            return new NavigationToCollectorBridge($container->getLocator()->collector()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideTouchQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideDataReaderService(Container $container)
    {
        $container[self::SERVICE_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };
    }

}
