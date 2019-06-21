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
    public const PLUGINS_SCHEDULE_READER = 'PLUGINS_SCHEDULE_READER';
    public const PLUGINS_SCHEDULER_ADAPTER = 'PLUGINS_SCHEDULER_ADAPTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addScheduleReaderPlugins($container);
        $container = $this->addSchedulerAdapterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addScheduleReaderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SCHEDULE_READER, function (Container $container) {
            return $this->getScheduleReaderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSchedulerAdapterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SCHEDULER_ADAPTER, function (Container $container) {
            return $this->getSchedulerAdapterPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Plugin\ScheduleReaderPluginInterface[]
     */
    protected function getScheduleReaderPlugins(): array
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
