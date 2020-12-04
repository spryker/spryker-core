<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceBridge;
use Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToMessengerFacadeBridge;
use Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeBridge;

/**
 * @method \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig getConfig()
 */
class SecurityOauthUserDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_USER = 'FACADE_USER';
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';
    public const SERVICE_UTIL_TEXT = 'UTIL_TEXT_SERVICE';

    public const PLUGINS_OAUTH_USER_CLIENT_STRATEGY = 'PLUGINS_OAUTH_USER_CLIENT_STRATEGY';
    public const PLUGINS_OAUTH_USER_RESTRICTION = 'PLUGINS_OAUTH_USER_RESTRICTION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addUserFacade($container);
        $container = $this->addMessengerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addUserFacade($container);
        $container = $this->addUtilTextService($container);
        $container = $this->addOauthUserClientStrategyPlugins($container);
        $container = $this->addOauthUserRestrictionPlugins($container);

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
            return new SecurityOauthUserToUserFacadeBridge(
                $container->getLocator()->user()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container->set(static::FACADE_MESSENGER, function (Container $container) {
            return new SecurityOauthUserToMessengerFacadeBridge(
                $container->getLocator()->messenger()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new MerchantUserToUtilTextServiceBridge(
                $container->getLocator()->utilText()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthUserClientStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_USER_CLIENT_STRATEGY, function () {
            return $this->getOauthUserClientStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserClientStrategyPluginInterface[]
     */
    protected function getOauthUserClientStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthUserRestrictionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_USER_RESTRICTION, function () {
            return $this->getOauthUserRestrictionPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserRestrictionPluginInterface[]
     */
    protected function getOauthUserRestrictionPlugins(): array
    {
        return [];
    }
}
