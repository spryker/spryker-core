<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\OauthClient\OauthClientConfig getConfig()
 */
class OauthClientDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_OAUTH_ACCESS_TOKEN_PROVIDER = 'PLUGINS_OAUTH_ACCESS_TOKEN_PROVIDER';

    /**
     * @var string
     */
    public const PLUGINS_ACCESS_TOKEN_REQUEST_EXPANDER = 'PLUGINS_ACCESS_TOKEN_REQUEST_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addOauthAccessTokenProviderPlugins($container);
        $container = $this->addAccessTokenRequestExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthAccessTokenProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_ACCESS_TOKEN_PROVIDER, function () {
            return $this->getOauthAccessTokenProviderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\OauthClientExtension\Dependency\Plugin\OauthAccessTokenProviderPluginInterface>
     */
    protected function getOauthAccessTokenProviderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAccessTokenRequestExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ACCESS_TOKEN_REQUEST_EXPANDER, function () {
            return $this->getAccessTokenRequestExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\OauthClientExtension\Dependency\Plugin\AccessTokenRequestExpanderPluginInterface>
     */
    protected function getAccessTokenRequestExpanderPlugins(): array
    {
        return [];
    }
}
