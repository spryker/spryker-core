<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Authorization;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class AuthorizationDependencyProvider extends AbstractDependencyProvider
{
    public const PLUGINS_AUTHORIZATION_STRATEGIES = 'PLUGINS_AUTHORIZATION_STRATEGIES';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addAuthorizationStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addAuthorizationStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_AUTHORIZATION_STRATEGIES, function () {
            return $this->getAuthorizationStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface[]
     */
    protected function getAuthorizationStrategyPlugins(): array
    {
        return [];
    }
}
