<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreBridge;

/**
 * @method \Spryker\Zed\Scheduler\SchedulerConfig getConfig()
 */
class SchedulerDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SCHEDULER_READER_PLUGINS = 'SCHEDULER_READER_PLUGINS';
    public const SCHEDULER_ADAPTER_PLUGINS = 'SCHEDULER_ADAPTER_PLUGINS';

    public const STORE = 'STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addStore($container);
        $container = $this->addReaderPlugins($container);
        $container = $this->addAdapterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addStore(Container $container): Container
    {
        $container[static::STORE] = function (Container $container) {
            return new SchedulerToStoreBridge(Store::getInstance());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addReaderPlugins(Container $container): Container
    {
        $container[static::SCHEDULER_READER_PLUGINS] = function (Container $container) {
            return $this->getReaderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAdapterPlugins(Container $container): Container
    {
        $container[static::SCHEDULER_ADAPTER_PLUGINS] = function (Container $container) {
            return $this->getAdapterPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerReaderPluginInterface[]
     */
    protected function getReaderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Adapter\SchedulerAdapterPluginInterface[]
     */
    protected function getAdapterPlugins(): array
    {
        return [];
    }
}
