<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCollector;

use Spryker\Zed\CmsBlockCollector\Dependency\Facade\CmsBlockCollectorToCollectorBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsBlockCollectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COLLECTOR = 'CMS_BLOCK_COLLECTOR:FACADE_COLLECTOR';

    public const QUERY_CONTAINER_TOUCH = 'CMS_BLOCK_COLLECTOR:QUERY_CONTAINER_TOUCH';

    public const SERVICE_DATA_READER = 'CMS_BLOCK_COLLECTOR:SERVICE_DATA_READER';

    public const COLLECTOR_DATA_EXPANDER_PLUGINS = 'CMS_BLOCK_COLLECTOR:DATA_EXPANDER_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addUtilDataReaderService($container);
        $container = $this->addCollectorFacade($container);
        $container = $this->addTouchQueryContainer($container);
        $container = $this->addCollectorDataExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTouchQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCollectorFacade(Container $container)
    {
        $container[static::FACADE_COLLECTOR] = function (Container $container) {
            return new CmsBlockCollectorToCollectorBridge($container->getLocator()->collector()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDataReaderService(Container $container)
    {
        $container[static::SERVICE_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCollectorDataExpanderPlugins(Container $container)
    {
        $container[static::COLLECTOR_DATA_EXPANDER_PLUGINS] = function (Container $container) {
            return $this->getCollectorDataExpanderPlugins();
        };

        return $container;
    }

    /**
     * Stack of plugins which run during data collection for each item.
     *
     * @return \Spryker\Zed\CmsBlockCollector\Dependency\Plugin\CmsBlockCollectorDataExpanderPluginInterface[]
     */
    protected function getCollectorDataExpanderPlugins()
    {
        return [];
    }
}
