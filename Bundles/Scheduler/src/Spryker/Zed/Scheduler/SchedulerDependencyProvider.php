<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Scheduler\SchedulerConfig getConfig()
 */
class SchedulerDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_SCHEDULE_READER = 'SCHEDULER::PLUGINS_SCHEDULE_READER';
    public const PLUGINS_SCHEDULER_ADAPTER = 'SCHEDULER::PLUGINS_SCHEDULER_ADAPTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addReaderPlugins($container);
        $container = $this->addAdapterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addReaderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SCHEDULE_READER, function (Container $container) {
            return $this->getSchedulerReaderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAdapterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SCHEDULER_ADAPTER, function (Container $container) {
            return $this->getSchedulerAdapterPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Plugin\ScheduleReaderPluginInterface[]
     */
    protected function getSchedulerReaderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerAdapterPluginInterface[]
     */
    protected function getSchedulerAdapterPlugins(): array
    {
        return [];
    }
}
