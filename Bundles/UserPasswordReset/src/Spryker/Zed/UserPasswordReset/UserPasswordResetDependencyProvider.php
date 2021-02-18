<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordReset;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\UserPasswordReset\Dependency\Facade\UserPasswordResetToUserFacadeBridge;
use Spryker\Zed\UserPasswordReset\Dependency\Service\UserPasswordResetToUtilTextServiceBridge;

/**
 * @method \Spryker\Zed\UserPasswordReset\UserPasswordResetConfig getConfig()
 */
class UserPasswordResetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_USER = 'FACADE_USER';
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';
    public const PLUGINS_USER_PASSWORD_RESET_REQUEST_HANDLER = 'PLUGINS_USER_PASSWORD_RESET_REQUEST_HANDLER';

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
        $container = $this->addUserPasswordResetRequestStrategyPlugins($container);

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
            return new UserPasswordResetToUserFacadeBridge(
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
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new UserPasswordResetToUtilTextServiceBridge(
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
    protected function addUserPasswordResetRequestStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_USER_PASSWORD_RESET_REQUEST_HANDLER, function () {
            return $this->getUserPasswordResetRequestStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\UserPasswordResetExtension\Dependency\Plugin\UserPasswordResetRequestStrategyPluginInterface[]
     */
    public function getUserPasswordResetRequestStrategyPlugins(): array
    {
        return [];
    }
}
