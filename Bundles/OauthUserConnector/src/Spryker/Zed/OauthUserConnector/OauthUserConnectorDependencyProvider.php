<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\OauthUserConnector\Dependency\Facade\OauthUserConnectorToOauthFacadeBridge;
use Spryker\Zed\OauthUserConnector\Dependency\Facade\OauthUserConnectorToUserFacadeBridge;
use Spryker\Zed\OauthUserConnector\Dependency\Service\OauthUserConnectorToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig getConfig()
 */
class OauthUserConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @var string
     */
    public const FACADE_OAUTH = 'FACADE_OAUTH';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const PLUGINS_USER_TYPE_OAUTH_SCOPE_PROVIDER = 'PLUGINS_USER_TYPE_OAUTH_SCOPE_PROVIDER';

    /**
     * @var string
     */
    public const PLUGINS_USER_TYPE_OAUTH_SCOPE_AUTHORIZATION_CHECKER = 'PLUGINS_USER_TYPE_OAUTH_SCOPE_AUTHORIZATION_CHECKER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addUserFacade($container);
        $container = $this->addOauthFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addUserTypeOauthScopeProviderPlugins($container);
        $container = $this->addUserTypeOauthScopeAuthorizationCheckerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new OauthUserConnectorToUserFacadeBridge($container->getLocator()->user()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthFacade(Container $container): Container
    {
        $container->set(static::FACADE_OAUTH, function (Container $container) {
            return new OauthUserConnectorToOauthFacadeBridge($container->getLocator()->oauth()->facade());
        });

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
            return new OauthUserConnectorToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserTypeOauthScopeProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_USER_TYPE_OAUTH_SCOPE_PROVIDER, function () {
            return $this->getUserTypeOauthScopeProviderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeProviderPluginInterface>
     */
    protected function getUserTypeOauthScopeProviderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserTypeOauthScopeAuthorizationCheckerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_USER_TYPE_OAUTH_SCOPE_AUTHORIZATION_CHECKER, function () {
            return $this->getUserTypeOauthScopeAuthorizationCheckerPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeAuthorizationCheckerPluginInterface>
     */
    protected function getUserTypeOauthScopeAuthorizationCheckerPlugins(): array
    {
        return [];
    }
}
