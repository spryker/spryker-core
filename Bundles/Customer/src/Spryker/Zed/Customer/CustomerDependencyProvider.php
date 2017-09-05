<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryBridge;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleBridge;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailBridge;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToSequenceNumberBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomerDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_SEQUENCE_NUMBER = 'sequence number facade';
    const FACADE_COUNTRY = 'country facade';
    const FACADE_LOCALE = 'locale facade';
    const FACADE_MAIL = 'mail facade';
    const SERVICE_DATE_FORMATTER = 'date formatter service';
    const QUERY_CONTAINER_LOCALE = 'locale query container';
    const STORE = 'store';

    const PLUGINS_CUSTOMER_ANONYMIZER = 'PLUGINS_CUSTOMER_ANONYMIZER';
    const PLUGINS_CUSTOMER_TRANSFER_EXPANDER = 'PLUGINS_CUSTOMER_TRANSFER_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::FACADE_SEQUENCE_NUMBER] = function (Container $container) {
            return new CustomerToSequenceNumberBridge($container->getLocator()->sequenceNumber()->facade());
        };

        $container[static::FACADE_COUNTRY] = function (Container $container) {
            return new CustomerToCountryBridge($container->getLocator()->country()->facade());
        };

        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new CustomerToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[static::FACADE_MAIL] = function (Container $container) {
            return new CustomerToMailBridge($container->getLocator()->mail()->facade());
        };

        $container[static::QUERY_CONTAINER_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->queryContainer();
        };

        $container = $this->addStore($container);
        $container = $this->addCustomerAnonymizerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_COUNTRY] = function (Container $container) {
            return new CustomerToCountryBridge($container->getLocator()->country()->facade());
        };
        $container[self::SERVICE_DATE_FORMATTER] = function (Container $container) {
            return $container->getLocator()->utilDateTime()->service();
        };

        $container = $this->addStore($container);
        $container = $this->addCustomerTransferExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerAnonymizerPlugins(Container $container)
    {
        $container[static::PLUGINS_CUSTOMER_ANONYMIZER] = function (Container $container) {
            return $this->getCustomerAnonymizerPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Plugin\CustomerAnonymizerPluginInterface[]
     */
    protected function getCustomerAnonymizerPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function (Container $container) {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addCustomerTransferExpanderPlugins(Container $container)
    {
        $container[static::PLUGINS_CUSTOMER_TRANSFER_EXPANDER] = function (Container $container) {
            return $this->getCustomerTransferExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface[]
     */
    protected function getCustomerTransferExpanderPlugins()
    {
        return [];
    }

}
