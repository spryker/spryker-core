<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class OauthDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_USER_PROVIDER = 'PLUGIN_USER_PROVIDER';
    public const PLUGIN_SCOPE_PROVIDER = 'PLUGIN_SCOPE_PROVIDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addUserProviderPlugins($container);
        $container = $this->addScopeProviderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserProviderPlugins(Container $container): Container
    {
        $container[static::PLUGIN_USER_PROVIDER] = function (Container $container) {
            return $this->getUserProviderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addScopeProviderPlugins(Container $container): Container
    {
        $container[static::PLUGIN_SCOPE_PROVIDER] = function (Container $container) {
            return $this->getScopeProviderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Oauth\Dependency\Plugin\UserProviderPluginInterface[]
     */
    protected function getUserProviderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Oauth\Dependency\Plugin\ScopeProviderPluginInterface[]
     */
    protected function getScopeProviderPlugins(): array
    {
        return [];
    }
}
