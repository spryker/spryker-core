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
    public const PLUGINS_OAUTH_REFRESH_TOKEN_REVOKER = 'PLUGINS_OAUTH_REFRESH_TOKEN_REVOKER';
    public const PLUGINS_OAUTH_REFRESH_TOKENS_REVOKER = 'PLUGINS_OAUTH_REFRESH_TOKENS_REVOKER';
    public const PLUGINS_OAUTH_REFRESH_TOKEN_SAVER = 'PLUGINS_OAUTH_REFRESH_TOKEN_SAVER';
    public const PLUGINS_OAUTH_REFRESH_TOKEN_PERSISTENCE = 'PLUGINS_OAUTH_REFRESH_TOKEN_PERSISTENCE';
    public const PLUGINS_OAUTH_REFRESH_TOKEN_CHECKER = 'PLUGINS_OAUTH_REFRESH_TOKEN_CHECKER';
    public const PLUGINS_OAUTH_EXPIRED_REFRESH_TOKEN_REMOVER = 'PLUGINS_OAUTH_EXPIRED_REFRESH_TOKEN_REMOVER';
    public const PLUGINS_OAUTH_REFRESH_TOKEN_READER = 'PLUGINS_OAUTH_REFRESH_TOKEN_READER';
    public const PLUGINS_OAUTH_REFRESH_TOKENS_READER = 'PLUGINS_OAUTH_REFRESH_TOKENS_READER';

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
        $container = $this->addOauthRefreshTokenRevokerPlugins($container);
        $container = $this->addOauthRefreshTokensRevokerPlugins($container);
        $container = $this->addOauthRefreshTokenSaverPlugins($container);
        $container = $this->addOauthRefreshTokenPersistencePlugins($container);
        $container = $this->addOauthRefreshTokenCheckerPlugins($container);
        $container = $this->addOauthExpiredRefreshTokenRemoverPlugins($container);
        $container = $this->addOauthRefreshTokenReaderPlugins($container);
        $container = $this->addOauthRefreshTokensReaderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new OauthToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_USER_PROVIDER, function (Container $container) {
            return $this->getUserProviderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addScopeProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_SCOPE_PROVIDER, function (Container $container) {
            return $this->getScopeProviderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGrantTypeConfigurationProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_GRANT_TYPE_CONFIGURATION_PROVIDER, function (Container $container) {
            return $this->getGrantTypeConfigurationProviderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthUserIdentifierFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_USER_IDENTIFIER_FILTER, function () {
            return $this->getOauthUserIdentifierFilterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthRefreshTokenRevokerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_REFRESH_TOKEN_REVOKER, function () {
            return $this->getOauthRefreshTokenRevokerPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthRefreshTokensRevokerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_REFRESH_TOKENS_REVOKER, function () {
            return $this->getOauthRefreshTokensRevokerPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Oauth\OauthDependencyProvider::addOauthRefreshTokenPersistencePlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthRefreshTokenSaverPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_REFRESH_TOKEN_SAVER, function () {
            return $this->getOauthRefreshTokenSaverPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthRefreshTokenPersistencePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_REFRESH_TOKEN_PERSISTENCE, function () {
            return $this->getOauthRefreshTokenPersistencePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthRefreshTokenCheckerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_REFRESH_TOKEN_CHECKER, function () {
            return $this->getOauthRefreshTokenCheckerPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthExpiredRefreshTokenRemoverPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_EXPIRED_REFRESH_TOKEN_REMOVER, function () {
            return $this->getOauthExpiredRefreshTokenRemoverPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthRefreshTokenReaderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_REFRESH_TOKEN_READER, function () {
            return $this->getOauthRefreshTokenReaderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthRefreshTokensReaderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_REFRESH_TOKENS_READER, function () {
            return $this->getOauthRefreshTokensReaderPlugins();
        });

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
     * @deprecated Use {@link \Spryker\Zed\Oauth\OauthDependencyProvider::getOauthRefreshTokenPersistencePlugins()} instead.
     *
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenSaverPluginInterface[]
     */
    protected function getOauthRefreshTokenSaverPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenPersistancePluginInterface[]
     */
    protected function getOauthRefreshTokenPersistencePlugins(): array
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

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthExpiredRefreshTokenRemoverPluginInterface[]
     */
    protected function getOauthExpiredRefreshTokenRemoverPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenReaderPluginInterface[]
     */
    protected function getOauthRefreshTokenReaderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensReaderPluginInterface[]
     */
    protected function getOauthRefreshTokensReaderPlugins(): array
    {
        return [];
    }
}
