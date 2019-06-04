<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer;

use Spryker\Client\Customer\CustomerAddress\CustomerAddress;
use Spryker\Client\Customer\CustomerSecuredPattern\CustomerSecuredPattern;
use Spryker\Client\Customer\CustomerSecuredPattern\CustomerSecuredPatternInterface;
use Spryker\Client\Customer\Reader\CustomerAccessTokenReader;
use Spryker\Client\Customer\Reader\CustomerAccessTokenReaderInterface;
use Spryker\Client\Customer\Session\CustomerSession;
use Spryker\Client\Customer\Zed\CustomerStub;
use Spryker\Client\CustomerExtension\Dependency\Plugin\AccessTokenAuthenticationHandlerPluginInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\Customer\CustomerConfig getConfig()
 */
class CustomerFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Customer\Zed\CustomerStubInterface
     */
    public function createZedCustomerStub()
    {
        return new CustomerStub($this->getProvidedDependency(CustomerDependencyProvider::SERVICE_ZED));
    }

    /**
     * @return \Spryker\Client\Customer\CustomerAddress\CustomerAddressInterface
     */
    public function createCustomerAddress()
    {
        return new CustomerAddress(
            $this->createZedCustomerStub(),
            $this->getDefaultAddressChangePlugins()
        );
    }

    /**
     * @return \Spryker\Client\Customer\Session\CustomerSessionInterface
     */
    public function createSessionCustomerSession()
    {
        return new CustomerSession(
            $this->getSessionClient(),
            $this->getCustomerSessionGetPlugins(),
            $this->getCustomerSessionSetPlugin()
        );
    }

    /**
     * @return \Spryker\Client\Customer\Reader\CustomerAccessTokenReaderInterface
     */
    public function createCustomerAccessTokenReader(): CustomerAccessTokenReaderInterface
    {
        return new CustomerAccessTokenReader(
            $this->getAccessTokenAuthenticationHandlerPlugin()
        );
    }

    /**
     * @return \Spryker\Client\Customer\Dependency\Plugin\CustomerSessionGetPluginInterface[]
     */
    public function getCustomerSessionGetPlugins()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_CUSTOMER_SESSION_GET);
    }

    /**
     * @return \Spryker\Client\Customer\Dependency\Plugin\CustomerSessionSetPluginInterface[]
     */
    public function getCustomerSessionSetPlugin()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_CUSTOMER_SESSION_SET);
    }

    /**
     * @return \Spryker\Client\Customer\Dependency\Plugin\DefaultAddressChangePluginInterface[]
     */
    public function getDefaultAddressChangePlugins()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_DEFAULT_ADDRESS_CHANGE);
    }

    /**
     * @return \Spryker\Client\Customer\CustomerSecuredPattern\CustomerSecuredPatternInterface
     */
    public function createCustomerSecuredPattern(): CustomerSecuredPatternInterface
    {
        return new CustomerSecuredPattern($this->getConfig(), $this->getCustomerSecuredPatternRulePlugins());
    }

    /**
     * @return \Spryker\Client\CustomerExtension\Dependency\Plugin\CustomerSecuredPatternRulePluginInterface[]
     */
    public function getCustomerSecuredPatternRulePlugins(): array
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_CUSTOMER_SECURED_PATTERN_RULE);
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    protected function getSessionClient()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_SESSION);
    }

    /**
     * @return \Spryker\Client\CustomerExtension\Dependency\Plugin\AccessTokenAuthenticationHandlerPluginInterface
     */
    public function getAccessTokenAuthenticationHandlerPlugin(): AccessTokenAuthenticationHandlerPluginInterface
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGIN_ACCESS_TOKEN_AUTHENTICATION_HANDLER);
    }
}
