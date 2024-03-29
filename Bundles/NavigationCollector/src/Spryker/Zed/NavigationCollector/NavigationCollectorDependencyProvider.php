<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationCollector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\NavigationCollector\Dependency\Facade\NavigationCollectorToCollectorBridge;
use Spryker\Zed\NavigationCollector\Dependency\Facade\NavigationCollectorToNavigationBridge;

/**
 * @method \Spryker\Zed\NavigationCollector\NavigationCollectorConfig getConfig()
 */
class NavigationCollectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_COLLECTOR = 'FACADE_COLLECTOR';

    /**
     * @var string
     */
    public const FACADE_NAVIGATION = 'FACADE_NAVIGATION';

    /**
     * @var string
     */
    public const SERVICE_DATA_READER = 'SERVICE_DATA_READER';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_TOUCH = 'QUERY_CONTAINER_TOUCH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->provideCollectorFacade($container);
        $this->provideNavigationFacade($container);
        $this->provideDataReaderService($container);
        $this->provideTouchQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideCollectorFacade(Container $container)
    {
        $container->set(static::FACADE_COLLECTOR, function (Container $container) {
            return new NavigationCollectorToCollectorBridge($container->getLocator()->collector()->facade());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideNavigationFacade(Container $container)
    {
        $container->set(static::FACADE_NAVIGATION, function (Container $container) {
            return new NavigationCollectorToNavigationBridge($container->getLocator()->navigation()->facade());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideDataReaderService(Container $container)
    {
        $container->set(static::SERVICE_DATA_READER, function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideTouchQueryContainer(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_TOUCH, function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        });
    }
}
