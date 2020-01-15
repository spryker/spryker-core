<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WebProfiler;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\WebProfiler\WebProfilerConfig getConfig()
 */
class WebProfilerDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @deprecated Use `\Spryker\Zed\WebProfiler\WebProfilerDependencyProvider::PLUGINS_DATA_COLLECTORS` instead.
     */
    public const PLUGINS_WEB_PROFILER = 'PLUGINS_WEB_PROFILER';
    public const PLUGINS_DATA_COLLECTORS = 'PLUGINS_DATA_COLLECTORS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addDataCollectorPlugins($container);

        $container->set(static::PLUGINS_WEB_PROFILER, function () {
            return $this->getWebProfilerPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataCollectorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_DATA_COLLECTORS, function () {
            return $this->getDataCollectorPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\WebProfilerExtension\Dependency\Plugin\WebProfilerDataCollectorPluginInterface[]
     */
    public function getDataCollectorPlugins()
    {
        return [];
    }

    /**
     * @deprecated Use `\Spryker\Zed\WebProfiler\WebProfilerDependencyProvider::getDataCollectorPlugins()` instead.
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    public function getWebProfilerPlugins()
    {
        return [];
    }
}
