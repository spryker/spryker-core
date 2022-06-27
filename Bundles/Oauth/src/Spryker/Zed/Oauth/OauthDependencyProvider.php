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
use Spryker\Zed\Oauth\Dependency\External\OauthToSymfonyFilesystemAdapter;
use Spryker\Zed\Oauth\Dependency\External\OauthToYamlAdapter;
use Spryker\Zed\Oauth\Dependency\Service\OauthToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\Oauth\OauthConfig getConfig()
 */
class OauthDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const PLUGIN_USER_PROVIDER = 'PLUGIN_USER_PROVIDER';

    /**
     * @var string
     */
    public const PLUGINS_OAUTH_USER_PROVIDER = 'PLUGINS_OAUTH_USER_PROVIDER';

    /**
     * @var string
     */
    public const PLUGIN_SCOPE_PROVIDER = 'PLUGIN_SCOPE_PROVIDER';

    /**
     * @var string
     */
    public const PLUGINS_SCOPE_FINDER = 'PLUGINS_SCOPE_FINDER';

    /**
     * @var string
     */
    public const PLUGINS_GRANT_TYPE_CONFIGURATION_PROVIDER = 'PLUGINS_GRANT_TYPE_CONFIGURATION_PROVIDER';

    /**
     * @var string
     */
    public const PLUGINS_OAUTH_REQUEST_GRANT_TYPE_CONFIGURATION_PROVIDER = 'PLUGINS_OAUTH_REQUEST_GRANT_TYPE_CONFIGURATION_PROVIDER';

    /**
     * @var string
     */
    public const PLUGINS_OAUTH_USER_IDENTIFIER_FILTER = 'PLUGINS_OAUTH_USER_IDENTIFIER_FILTER';

    /**
     * @var string
     */
    public const PLUGINS_OAUTH_REFRESH_TOKEN_REVOKER = 'PLUGINS_OAUTH_REFRESH_TOKEN_REVOKER';

    /**
     * @var string
     */
    public const PLUGINS_OAUTH_REFRESH_TOKENS_REVOKER = 'PLUGINS_OAUTH_REFRESH_TOKENS_REVOKER';

    /**
     * @var string
     */
    public const PLUGINS_OAUTH_REFRESH_TOKEN_SAVER = 'PLUGINS_OAUTH_REFRESH_TOKEN_SAVER';

    /**
     * @var string
     */
    public const PLUGINS_OAUTH_REFRESH_TOKEN_PERSISTENCE = 'PLUGINS_OAUTH_REFRESH_TOKEN_PERSISTENCE';

    /**
     * @var string
     */
    public const PLUGINS_OAUTH_REFRESH_TOKEN_CHECKER = 'PLUGINS_OAUTH_REFRESH_TOKEN_CHECKER';

    /**
     * @var string
     */
    public const PLUGINS_OAUTH_EXPIRED_REFRESH_TOKEN_REMOVER = 'PLUGINS_OAUTH_EXPIRED_REFRESH_TOKEN_REMOVER';

    /**
     * @var string
     */
    public const PLUGINS_OAUTH_REFRESH_TOKEN_READER = 'PLUGINS_OAUTH_REFRESH_TOKEN_READER';

    /**
     * @var string
     */
    public const PLUGINS_OAUTH_REFRESH_TOKENS_READER = 'PLUGINS_OAUTH_REFRESH_TOKENS_READER';

    /**
     * @var string
     */
    public const PLUGINS_SCOPE_COLLECTOR = 'PLUGINS_SCOPE_COLLECTOR';

    /**
     * @var string
     */
    public const FILESYSTEM = 'FILESYSTEM';

    /**
     * @var string
     */
    public const YAML_DUMPER = 'YAML_DUMPER';

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
        $container = $this->addOauthUserProviderPlugins($container);
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
        $container = $this->addOauthRequestGrantTypeConfigurationProviderPlugins($container);
        $container = $this->addScopeCollectorPlugins($container);
        $container = $this->addFilesystem($container);
        $container = $this->addYamlDumper($container);
        $container = $this->addScopeFinderPlugins($container);

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
                $container->getLocator()->utilEncoding()->service(),
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
    protected function addOauthUserProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_USER_PROVIDER, function (Container $container) {
            return $this->getOauthUserProviderPlugins();
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
    protected function addScopeFinderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SCOPE_FINDER, function (Container $container) {
            return $this->getScopeFinderPlugins();
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
    protected function addOauthRequestGrantTypeConfigurationProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_REQUEST_GRANT_TYPE_CONFIGURATION_PROVIDER, function (Container $container) {
            return $this->getOauthRequestGrantTypeConfigurationProviderPlugins();
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
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addScopeCollectorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SCOPE_COLLECTOR, function (Container $container) {
            return $this->getScopeCollectorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFilesystem(Container $container): Container
    {
        $container->set(static::FILESYSTEM, function () {
            return new OauthToSymfonyFilesystemAdapter();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addYamlDumper(Container $container): Container
    {
        $container->set(static::YAML_DUMPER, function () {
            return new OauthToYamlAdapter();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface>
     */
    protected function getUserProviderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface>
     */
    protected function getOauthUserProviderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface>
     */
    protected function getScopeProviderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeFinderPluginInterface>
     */
    protected function getScopeFinderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeConfigurationProviderPluginInterface>
     */
    protected function getGrantTypeConfigurationProviderPlugins(): array
    {
        return [
            new PasswordOauthGrantTypeConfigurationProviderPlugin(),
            new RefreshTokenOauthGrantTypeConfigurationProviderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRequestGrantTypeConfigurationProviderPluginInterface>
     */
    protected function getOauthRequestGrantTypeConfigurationProviderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface>
     */
    protected function getOauthUserIdentifierFilterPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenRevokerPluginInterface>
     */
    protected function getOauthRefreshTokenRevokerPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensRevokerPluginInterface>
     */
    protected function getOauthRefreshTokensRevokerPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Oauth\OauthDependencyProvider::getOauthRefreshTokenPersistencePlugins()} instead.
     *
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenSaverPluginInterface>
     */
    protected function getOauthRefreshTokenSaverPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenPersistencePluginInterface>
     */
    protected function getOauthRefreshTokenPersistencePlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenCheckerPluginInterface>
     */
    protected function getOauthRefreshTokenCheckerPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthExpiredRefreshTokenRemoverPluginInterface>
     */
    protected function getOauthExpiredRefreshTokenRemoverPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenReaderPluginInterface>
     */
    protected function getOauthRefreshTokenReaderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensReaderPluginInterface>
     */
    protected function getOauthRefreshTokensReaderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeCollectorPluginInterface>
     */
    protected function getScopeCollectorPlugins(): array
    {
        return [];
    }
}
