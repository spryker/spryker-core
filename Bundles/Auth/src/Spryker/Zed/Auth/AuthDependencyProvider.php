<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth;

use Spryker\Zed\Auth\Dependency\Facade\AuthToUserBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Auth\Dependency\Plugin\AuthPasswordResetSenderInterface;

class AuthDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_USER = 'facade user';
    const PASSWORD_RESET_SENDER = 'Password reset sender';
    const CLIENT_SESSION = 'session client';

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
     * @return AuthPasswordResetSenderInterface|null;
     */
    protected function getPasswordResetNotificationSender(Container $container)
    {
        return null;
    }

}
