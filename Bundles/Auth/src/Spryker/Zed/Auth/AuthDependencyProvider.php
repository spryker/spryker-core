<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth;

use Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AuthDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_USER = 'facade user';
    public const PASSWORD_RESET_SENDER = 'Password reset sender';
    public const CLIENT_SESSION = 'session client';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_USER] = function (Container $container) {
            return new AuthToUserBridge($container->getLocator()->user()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_USER] = function (Container $container) {
            return new AuthToUserBridge($container->getLocator()->user()->facade());
        };

        $container[self::PASSWORD_RESET_SENDER] = function (Container $container) {
            return $this->getPasswordResetNotificationSender($container);
        };

        $container[self::CLIENT_SESSION] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Auth\Dependency\Plugin\AuthPasswordResetSenderInterface|null
     */
    protected function getPasswordResetNotificationSender(Container $container)
    {
        return null;
    }
}
