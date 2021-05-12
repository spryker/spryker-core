<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToMessengerFacadeBridge;
use Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToSecurityFacadeBridge;
use Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToUserFacadeBridge;
use Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToUserPasswordResetFacadeBridge;

/**
 * @method \Spryker\Zed\SecurityGui\SecurityGuiConfig getConfig()
 */
class SecurityGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_USER = 'FACADE_USER';
    public const FACADE_USER_PASSWORD_RESET = 'FACADE_USER_PASSWORD_RESET';
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';
    public const FACADE_SECURITY = 'FACADE_SECURITY';

    public const PLUGINS_AUTHENTICATION_LINK = 'PLUGINS_AUTHENTICATION_LINK';

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
    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new SecurityGuiToUserFacadeBridge(
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
            return new SecurityGuiToMessengerFacadeBridge(
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
    protected function addUserPasswordResetFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER_PASSWORD_RESET, function (Container $container) {
            return new SecurityGuiToUserPasswordResetFacadeBridge(
                $container->getLocator()->userPasswordReset()->facade()
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
                $container->getLocator()->security()->facade()
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
     * @return \Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\AuthenticationLinkPluginInterface[]
     */
    protected function getAuthenticationLinkPlugins(): array
    {
        return [];
    }
}
