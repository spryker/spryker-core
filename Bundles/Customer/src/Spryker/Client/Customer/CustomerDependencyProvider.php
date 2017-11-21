<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer;

use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionGetPluginInterface;
use Spryker\Client\Customer\Dependency\Plugin\DefaultAddressChangePluginInterface;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CustomerDependencyProvider extends AbstractDependencyProvider
{
    const SERVICE_SESSION = 'session service';
    const SERVICE_ZED = 'zed service';

    const PLUGINS_CUSTOMER_SESSION_GET = 'PLUGINS_CUSTOMER_SESSION_GET';
    const PLUGINS_CUSTOMER_SESSION_SET = 'PLUGINS_CUSTOMER_SESSION_SET';
    const PLUGINS_DEFAULT_ADDRESS_CHANGE = 'PLUGINS_DEFAULT_ADDRESS_CHANGE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[static::PLUGINS_DEFAULT_ADDRESS_CHANGE] = function (Container $container) {
            return $this->getDefaultAddressChangePlugins();
        };

        $container[self::SERVICE_SESSION] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

        $container[self::SERVICE_ZED] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };

        $container[self::PLUGINS_CUSTOMER_SESSION_GET] = function (Container $container) {
            return $this->getCustomerSessionGetPlugins();
        };

        $container[self::PLUGINS_CUSTOMER_SESSION_SET] = function (Container $container) {
            return $this->getCustomerSessionSetPlugins();
        };

        return $container;
    }

    /**
     * @return CustomerSessionGetPluginInterface[]
     */
    protected function getCustomerSessionGetPlugins()
    {
        return [];
    }

    /**
     * @return CustomerSessionGetPluginInterface[]
     */
    protected function getCustomerSessionSetPlugins()
    {
        return [];
    }

    /**
     * @return DefaultAddressChangePluginInterface[]
     */
    protected function getDefaultAddressChangePlugins()
    {
        return [];
    }
}
