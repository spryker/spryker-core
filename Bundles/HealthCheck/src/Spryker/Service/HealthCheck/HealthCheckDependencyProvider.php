<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

/**
 * @method \Spryker\Service\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_YVES_HEALTH_CHECK = 'PLUGINS_YVES_HEALTH_CHECK';
    public const PLUGINS_ZED_HEALTH_CHECK = 'PLUGINS_ZED_HEALTH_CHECK';
    public const PLUGINS_GLUE_HEALTH_CHECK = 'PLUGINS_GLUE_HEALTH_CHECK';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container): Container
    {
        $container = $this->addYvesHealthCheckPlugins($container);
        $container = $this->addZedHealthCheckPlugins($container);
        $container = $this->addGlueHealthCheckPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addYvesHealthCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_YVES_HEALTH_CHECK, function () {
            return $this->getYvesHealthCheckPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addZedHealthCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ZED_HEALTH_CHECK, function () {
            return $this->getZedHealthCheckPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addGlueHealthCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_GLUE_HEALTH_CHECK, function () {
            return $this->getGlueHealthCheckPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getYvesHealthCheckPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getZedHealthCheckPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getGlueHealthCheckPlugins(): array
    {
        return [];
    }
}
