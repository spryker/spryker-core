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
use Spryker\Zed\Customer\Dependency\Service\CustomerToUtilDateTimeServiceBridge;
use Spryker\Zed\Customer\Dependency\Service\CustomerToUtilSanitizeServiceBridge;
use Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\Container;

class CustomerDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_SEQUENCE_NUMBER = 'FACADE_SEQUENCE_NUMBER';
    const FACADE_COUNTRY = 'FACADE_COUNTRY';
    const FACADE_LOCALE = 'FACADE_LOCALE';
    const FACADE_MAIL = 'FACADE_MAIL';

    /**
     * @deprecated use SERVICE_UTIL_DATE_TIME instead
     */
    const SERVICE_DATE_FORMATTER = 'SERVICE_DATE_FORMATTER';
    const SERVICE_UTIL_VALIDATE = 'SERVICE_UTIL_VALIDATE';
    const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';

    const QUERY_CONTAINER_LOCALE = 'QUERY_CONTAINER_LOCALE';

    const STORE = 'STORE';

    const PLUGINS_CUSTOMER_ANONYMIZER = 'PLUGINS_CUSTOMER_ANONYMIZER';
    const PLUGINS_CUSTOMER_TRANSFER_EXPANDER = 'PLUGINS_CUSTOMER_TRANSFER_EXPANDER';
    const PLUGINS_POST_CUSTOMER_REGISTRATION = 'PLUGINS_POST_CUSTOMER_REGISTRATION';

    public const SUB_REQUEST_HANDLER = 'SUB_REQUEST_HANDLER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addSequenceNumberFacade($container);
        $container = $this->addCountryFacade($container);
        $container = $this->addMailFacade($container);
        $container = $this->addLocaleQueryConainer($container);
        $container = $this->addStore($container);
        $container = $this->addCustomerAnonymizerPlugins($container);
        $container = $this->addUtilValidateService($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addCustomerTransferExpanderPlugins($container);
        $container = $this->addPostCustomerRegistrationPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addCountryFacade($container);
        $container = $this->addDateFormatterService($container);
        $container = $this->addStore($container);
        $container = $this->addUtilSanitizeService($container);
        $container = $this->addUtilDateTimeService($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addSubRequestHandler($container);

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
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostCustomerRegistrationPlugins($container)
    {
        $container[static::PLUGINS_POST_CUSTOMER_REGISTRATION] = function () {
            return $this->getPostCustomerRegistrationPlugins();
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
    protected function addUtilValidateService(Container $container)
    {
        $container[static::SERVICE_UTIL_VALIDATE] = function (Container $container) {
            return new CustomerToUtilValidateServiceBridge($container->getLocator()->utilValidate()->service());
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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilSanitizeService(Container $container)
    {
        $container[static::SERVICE_UTIL_SANITIZE] = function (Container $container) {
            return new CustomerToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new CustomerToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSequenceNumberFacade(Container $container): Container
    {
        $container[static::FACADE_SEQUENCE_NUMBER] = function (Container $container) {
            return new CustomerToSequenceNumberBridge($container->getLocator()->sequenceNumber()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryFacade(Container $container): Container
    {
        $container[static::FACADE_COUNTRY] = function (Container $container) {
            return new CustomerToCountryBridge($container->getLocator()->country()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailFacade(Container $container): Container
    {
        $container[static::FACADE_MAIL] = function (Container $container) {
            return new CustomerToMailBridge($container->getLocator()->mail()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleQueryConainer(Container $container): Container
    {
        $container[static::QUERY_CONTAINER_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->queryContainer();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDateFormatterService(Container $container): Container
    {
        $container[static::SERVICE_DATE_FORMATTER] = function (Container $container) {
            return $container->getLocator()->utilDateTime()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDateTimeService($container): Container
    {
        $container[static::SERVICE_UTIL_DATE_TIME] = function (Container $container) {
            return new CustomerToUtilDateTimeServiceBridge($container->getLocator()->utilDateTime()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSubRequestHandler(Container $container): Container
    {
        $container[static::SUB_REQUEST_HANDLER] = function () {
            $pimple = new Pimple();
            return $pimple->getApplication()['sub_request'];
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CustomerExtension\Dependency\Plugin\PostCustomerRegistrationPluginInterface[]
     */
    protected function getPostCustomerRegistrationPlugins(): array
    {
        return [];
    }
}
