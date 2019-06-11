<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oauth\Communication\Plugin\Oauth\PasswordOauthGrantTypeConfigurationProviderPlugin;
use Spryker\Zed\Oauth\Communication\Plugin\Oauth\RefreshTokenOauthGrantTypeConfigurationProviderPlugin;
use Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\Oauth\OauthConfig getConfig()
 */
class OauthDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    public const PLUGIN_USER_PROVIDER = 'PLUGIN_USER_PROVIDER';
    public const PLUGIN_SCOPE_PROVIDER = 'PLUGIN_SCOPE_PROVIDER';
    public const PLUGINS_GRANT_TYPE_CONFIGURATION_PROVIDER = 'PLUGINS_GRANT_TYPE_CONFIGURATION_PROVIDER';
    public const PLUGINS_OAUTH_USER_IDENTIFIER_FILTER = 'PLUGINS_OAUTH_USER_IDENTIFIER_FILTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addUtilEncodingService($container);

        $container = $this->addUserProviderPlugins($container);
        $container = $this->addScopeProviderPlugins($container);
        $container = $this->addGrantTypeConfigurationProviderPlugins($container);
        $container = $this->addOauthUserIdentifierFilterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new OauthToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        };

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
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGrantTypeConfigurationProviderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_GRANT_TYPE_CONFIGURATION_PROVIDER] = function (Container $container) {
            return $this->getGrantTypeConfigurationProviderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthUserIdentifierFilterPlugins(Container $container): Container
    {
        $container[static::PLUGINS_OAUTH_USER_IDENTIFIER_FILTER] = function () {
            return $this->getOauthUserIdentifierFilterPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface[]
     */
    protected function getUserProviderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface[]
     */
    protected function getScopeProviderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeConfigurationProviderPluginInterface[]
     */
    protected function getGrantTypeConfigurationProviderPlugins(): array
    {
        return [
            new PasswordOauthGrantTypeConfigurationProviderPlugin(),
            new RefreshTokenOauthGrantTypeConfigurationProviderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface[]
     */
    protected function getOauthUserIdentifierFilterPlugins(): array
    {
        return [];
    }
}
