<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\HealthCheck;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * @method \Spryker\Yves\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_HEALTH_CHECK = 'PLUGINS_HEALTH_CHECK';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addHealthCheckPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addHealthCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_HEALTH_CHECK, function (Container $container) {
            return $this->getHealthCheckPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getHealthCheckPlugins(): array
    {
        return [];
    }
}
