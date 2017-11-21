<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer;

use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionGetPluginInterface;
use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionSetPluginInterface;
use Spryker\Client\Customer\Dependency\Plugin\DefaultAddressChangePluginInterface;
use Spryker\Client\Customer\Session\CustomerSession;
use Spryker\Client\Customer\Zed\CustomerStub;
use Spryker\Client\Kernel\AbstractFactory;

class CustomerFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Customer\Zed\CustomerStubInterface
     */
    public function createZedCustomerStub()
    {
        return new CustomerStub(
            $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_ZED),
            $this->getDefaultAddressChangePlugins()
        );
    }

    /**
     * @return \Spryker\Client\Customer\Session\CustomerSessionInterface
     */
    public function createSessionCustomerSession()
    {
        return new CustomerSession(
            $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_SESSION),
            $this->getCustomerSessionGetPlugins(),
            $this->getCustomerSessionSetPlugin()
        );
    }

    /**
     * @return CustomerSessionGetPluginInterface[]
     */
    public function getCustomerSessionGetPlugins()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_CUSTOMER_SESSION_GET);
    }

    /**
     * @return CustomerSessionSetPluginInterface[]
     */
    public function getCustomerSessionSetPlugin()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_CUSTOMER_SESSION_SET);
    }

    /**
     * @return DefaultAddressChangePluginInterface[]
     */
    public function getDefaultAddressChangePlugins()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_DEFAULT_ADDRESS_CHANGE);
    }
}
