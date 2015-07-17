<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class CustomerDependencyProvider extends AbstractBundleDependencyProvider
{

    const REGISTRATION_TOKEN_SENDERS = 'Registration Token Senders';
    const PASSWORD_RESTORE_TOKEN_SENDERS = 'Password Restore TokenSenders';
    const PASSWORD_RESTORED_CONFIRMATION_SENDERS = 'Password RestoredConfirmation Senders';
    const SENDER_PLUGINS = 'sender plugins';

    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::SENDER_PLUGINS] = function (Container $container) {
            return $this->getSenderPlugins($container);
        };

        return $container;
    }

    /**
     * Overwrite in project
     *
     * @param Container $container
     *
     * @return mixed[]
     */
    protected function getSenderPlugins(Container $container)
    {
        return [
            self::REGISTRATION_TOKEN_SENDERS => [],
            self::PASSWORD_RESTORE_TOKEN_SENDERS => [],
            self::PASSWORD_RESTORED_CONFIRMATION_SENDERS => [],
        ];
    }

}
