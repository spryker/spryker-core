<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SecurityGui\Dependency\Client\SecurityGuiToSecurityBlockerClientBridge;
use Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToMessengerFacadeBridge;
use Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToSecurityFacadeBridge;
use Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToUserFacadeBridge;
use Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToUserPasswordResetFacadeBridge;

/**
 * @method \Spryker\Zed\SecurityGui\SecurityGuiConfig getConfig()
 */
class SecurityGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SECURITY_BLOCKER = 'CLIENT_SECURITY_BLOCKER';

    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @var string
     */
    public const FACADE_USER_PASSWORD_RESET = 'FACADE_USER_PASSWORD_RESET';

    /**
     * @var string
     */
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';

    /**
     * @var string
     */
    public const FACADE_SECURITY = 'FACADE_SECURITY';

    /**
     * @var string
     */
    public const PLUGINS_AUTHENTICATION_LINK = 'PLUGINS_AUTHENTICATION_LINK';

    /**
     * @var string
     */
    public const PLUGINS_USER_ROLE_FILTER = 'PLUGINS_USER_ROLE_FILTER';

    /**
     * @var string
     */
    public const PLUGINS_USER_LOGIN_RESTRICTION = 'PLUGINS_USER_LOGIN_RESTRICTION';

    /**
     * @var string
     */
    public const PLUGINS_USER_AUTHENTICATION_HANDLER = 'PLUGINS_USER_AUTHENTICATION_HANDLER';

    /**
     * @uses {@link \Spryker\Zed\Security\Communication\Loader\Services\TokenStorageServiceLoader::SERVICE_SECURITY_TOKEN_STORAGE}
     *
     * @var string
     */
    public const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addUserFacade($container);
        $container = $this->addUserPasswordResetFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addSecurityFacade($container);
        $container = $this->addAuthenticationLinkPlugins($container);
        $container = $this->addUserRoleFilterPlugins($container);
        $container = $this->addUserLoginRestrictionPlugins($container);
        $container = $this->addSecurityBlockerClient($container);
        $container = $this->addUserAuthenticationHandlerPlugins($container);
        $container = $this->addSecurityTokenStorage($container);

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

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSecurityBlockerClient(Container $container)
    {
        $container->set(static::CLIENT_SECURITY_BLOCKER, function (Container $container) {
            return new SecurityGuiToSecurityBlockerClientBridge(
                $container->getLocator()->securityBlocker()->client(),
            );
        });

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
            return new SecurityGuiToUserFacadeBridge(
                $container->getLocator()->user()->facade(),
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
            return new SecurityGuiToMessengerFacadeBridge(
                $container->getLocator()->messenger()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserPasswordResetFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER_PASSWORD_RESET, function (Container $container) {
            return new SecurityGuiToUserPasswordResetFacadeBridge(
                $container->getLocator()->userPasswordReset()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSecurityFacade(Container $container): Container
    {
        $container->set(static::FACADE_SECURITY, function (Container $container) {
            return new SecurityGuiToSecurityFacadeBridge(
                $container->getLocator()->security()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAuthenticationLinkPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_AUTHENTICATION_LINK, function () {
            return $this->getAuthenticationLinkPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserRoleFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_USER_ROLE_FILTER, function () {
            return $this->getUserRoleFilterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserLoginRestrictionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_USER_LOGIN_RESTRICTION, function () {
            return $this->getUserLoginRestrictionPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserAuthenticationHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_USER_AUTHENTICATION_HANDLER, function () {
            return $this->getUserAuthenticationHandlerPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSecurityTokenStorage(Container $container): Container
    {
        $container->set(static::SERVICE_SECURITY_TOKEN_STORAGE, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_SECURITY_TOKEN_STORAGE);
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\AuthenticationLinkPluginInterface>
     */
    protected function getAuthenticationLinkPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\UserRoleFilterPluginInterface>
     */
    protected function getUserRoleFilterPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\UserLoginRestrictionPluginInterface>
     */
    protected function getUserLoginRestrictionPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\AuthenticationHandlerPluginInterface>
     */
    protected function getUserAuthenticationHandlerPlugins(): array
    {
        return [];
    }
}
