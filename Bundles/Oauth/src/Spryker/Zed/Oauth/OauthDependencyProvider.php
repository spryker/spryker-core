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
use Spryker\Zed\Oauth\Dependency\Facade\OauthToOauthRevokeFacadeBridge;
use Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\Oauth\OauthConfig getConfig()
 */
class OauthDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    public const FACADE_OAUTH_REVOKE = 'FACADE_OAUTH_REVOKE';

    public const PLUGIN_USER_PROVIDER = 'PLUGIN_USER_PROVIDER';
    public const PLUGIN_SCOPE_PROVIDER = 'PLUGIN_SCOPE_PROVIDER';
    public const PLUGINS_GRANT_TYPE_CONFIGURATION_PROVIDER = 'PLUGINS_GRANT_TYPE_CONFIGURATION_PROVIDER';
    public const PLUGINS_OAUTH_USER_IDENTIFIER_FILTER = 'PLUGINS_OAUTH_USER_IDENTIFIER_FILTER';
    public const PLUGINS_OAUTH_REFRESH_TOKEN_REVOKER = 'PLUGINS_OAUTH_REFRESH_TOKEN_REVOKER';
    public const PLUGINS_OAUTH_REFRESH_TOKENS_REVOKER = 'PLUGINS_OAUTH_REFRESH_TOKENS_REVOKER';
    public const PLUGINS_OAUTH_REFRESH_TOKEN_SAVER = 'PLUGINS_OAUTH_REFRESH_TOKEN_SAVER';
    public const PLUGINS_OAUTH_REFRESH_TOKEN_CHECKER = 'PLUGINS_OAUTH_REFRESH_TOKEN_CHECKER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addUtilEncodingService($container);

        $container = $this->addOauthRevokeFacade($container);

        $container = $this->addUserProviderPlugins($container);
        $container = $this->addScopeProviderPlugins($container);
        $container = $this->addGrantTypeConfigurationProviderPlugins($container);
        $container = $this->addOauthUserIdentifierFilterPlugins($container);
        $container = $this->addOauthRefreshTokenRevokerPlugins($container);
        $container = $this->addOauthRefreshTokensRevokerPlugins($container);
        $container = $this->addOauthRefreshTokenSaverPlugins($container);
        $container = $this->addOauthRefreshTokenCheckerPlugins($container);

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
    protected function addOauthRevokeFacade(Container $container): Container
    {
        $container[static::FACADE_OAUTH_REVOKE] = function (Container $container) {
            return new OauthToOauthRevokeFacadeBridge(
                $container->getLocator()->oauthRevoke()->facade()
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
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthRefreshTokenRevokerPlugins(Container $container): Container
    {
        $container[static::PLUGINS_OAUTH_REFRESH_TOKEN_REVOKER] = function () {
            return $this->getOauthRefreshTokenRevokerPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthRefreshTokensRevokerPlugins(Container $container): Container
    {
        $container[static::PLUGINS_OAUTH_REFRESH_TOKENS_REVOKER] = function () {
            return $this->getOauthRefreshTokensRevokerPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthRefreshTokenSaverPlugins(Container $container): Container
    {
        $container[static::PLUGINS_OAUTH_REFRESH_TOKEN_SAVER] = function () {
            return $this->getOauthRefreshTokenSaverPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthRefreshTokenCheckerPlugins(Container $container): Container
    {
        $container[static::PLUGINS_OAUTH_REFRESH_TOKEN_CHECKER] = function () {
            return $this->getOauthRefreshTokenCheckerPlugins();
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

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenRevokerPluginInterface[]
     */
    protected function getOauthRefreshTokenRevokerPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensRevokerPluginInterface[]
     */
    protected function getOauthRefreshTokensRevokerPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenSaverPluginInterface[]
     */
    protected function getOauthRefreshTokenSaverPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenCheckerPluginInterface[]
     */
    protected function getOauthRefreshTokenCheckerPlugins(): array
    {
        return [];
    }
}
