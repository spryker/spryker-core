<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class CustomerDependencyProvider extends AbstractBundleDependencyProvider
{
    const REGISTRATION_TOKEN_SENDER = 'RegistrationTokenSender';
    const PASSWORD_RESTORE_TOKEN_SENDER = 'PasswordRestoreTokenSender';
    const PASSWORD_RESTORED_CONFIRMATION_SENDER = 'PasswordRestoredConfirmationSender';

    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::REGISTRATION_TOKEN_SENDER] = function (Container $container) {
            return $container->getLocator()->customerMailConnector()->pluginRegistrationTokenSender();
        };
        $container[self::PASSWORD_RESTORE_TOKEN_SENDER] = function (Container $container) {
            return $container->getLocator()->customerMailConnector()->pluginPasswordRestoreTokenSender();
        };
        $container[self::PASSWORD_RESTORED_CONFIRMATION_SENDER] = function (Container $container) {
            return $container->getLocator()->customerMailConnector()->pluginPasswordRestoredConfirmationSender();
        };

        return $container;
    }
}
