<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CustomerDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_SESSION = 'session service';
    public const SERVICE_ZED = 'zed service';

    public const PLUGINS_CUSTOMER_SESSION_GET = 'PLUGINS_CUSTOMER_SESSION_GET';
    public const PLUGINS_CUSTOMER_SESSION_SET = 'PLUGINS_CUSTOMER_SESSION_SET';
    public const PLUGINS_DEFAULT_ADDRESS_CHANGE = 'PLUGINS_DEFAULT_ADDRESS_CHANGE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addDefaultAddressChangePlugins($container);
        $container = $this->addSessionClient($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addCustomerSessionGetPlugins($container);
        $container = $this->addCustomerSessionSetPlugins($container);

        return $container;
    }

    /**
     * @return \Spryker\Client\Customer\Dependency\Plugin\CustomerSessionGetPluginInterface[]
     */
    protected function getCustomerSessionGetPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Customer\Dependency\Plugin\CustomerSessionGetPluginInterface[]
     */
    protected function getCustomerSessionSetPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Client\Customer\Dependency\Plugin\DefaultAddressChangePluginInterface[]
     */
    protected function getDefaultAddressChangePlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addDefaultAddressChangePlugins(Container $container)
    {
        $container[static::PLUGINS_DEFAULT_ADDRESS_CHANGE] = function () {
            return $this->getDefaultAddressChangePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSessionClient(Container $container)
    {
        $container[static::SERVICE_SESSION] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container)
    {
        $container[static::SERVICE_ZED] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerSessionGetPlugins(Container $container)
    {
        $container[static::PLUGINS_CUSTOMER_SESSION_GET] = function () {
            return $this->getCustomerSessionGetPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerSessionSetPlugins(Container $container)
    {
        $container[static::PLUGINS_CUSTOMER_SESSION_SET] = function () {
            return $this->getCustomerSessionSetPlugins();
        };

        return $container;
    }
}
