<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer;

use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryBridge;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleBridge;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToSequenceNumberBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomerDependencyProvider extends AbstractBundleDependencyProvider
{

    const REGISTRATION_TOKEN_SENDERS = 'Registration Token Senders';
    const PASSWORD_RESTORE_TOKEN_SENDERS = 'Password Restore TokenSenders';
    const PASSWORD_RESTORED_CONFIRMATION_SENDERS = 'Password RestoredConfirmation Senders';
    const SENDER_PLUGINS = 'sender plugins';
    const FACADE_SEQUENCE_NUMBER = 'sequence number facade';
    const FACADE_COUNTRY = 'country facade';
    const FACADE_LOCALE = 'locale facade';
    const SERVICE_DATE_FORMATTER = 'date formatter service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::SENDER_PLUGINS] = function (Container $container) {
            return $this->getSenderPlugins($container);
        };

        $container[self::FACADE_SEQUENCE_NUMBER] = function (Container $container) {
            return new CustomerToSequenceNumberBridge($container->getLocator()->sequenceNumber()->facade());
        };

        $container[self::FACADE_COUNTRY] = function (Container $container) {
            return new CustomerToCountryBridge($container->getLocator()->country()->facade());
        };
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new CustomerToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_COUNTRY] = function (Container $container) {
            return new CustomerToCountryBridge($container->getLocator()->country()->facade());
        };

        $container[self::SERVICE_DATE_FORMATTER] = function () {
            return (new Pimple())->getApplication()['dateFormatter'];
        };

        return $container;
    }

    /**
     * Overwrite in project
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
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
