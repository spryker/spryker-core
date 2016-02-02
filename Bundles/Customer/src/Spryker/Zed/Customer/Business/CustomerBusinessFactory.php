<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Customer\Business\Customer\Customer;
use Spryker\Zed\Customer\Business\Customer\Address;
use Spryker\Zed\Customer\CustomerConfig;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGenerator;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainer;

/**
 * @method CustomerConfig getConfig()
 * @method CustomerQueryContainer getQueryContainer()
 */
class CustomerBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @deprecated Use getQueryContainer() instead.
     *
     * @return \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    public function createQueryContainer()
    {
        trigger_error('Deprecated, use getQueryContainer() instead.', E_USER_DEPRECATED);

        return $this->getQueryContainer();
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Customer\Customer
     */
    public function createCustomer()
    {
        $config = $this->getConfig();
        $senderPlugins = $this->getProvidedDependency(CustomerDependencyProvider::SENDER_PLUGINS);

        $customer = new Customer(
            $this->getQueryContainer(),
            $this->createCustomerReferenceGenerator(),
            $config
        );

        foreach ($senderPlugins[CustomerDependencyProvider::REGISTRATION_TOKEN_SENDERS] as $sender) {
            $customer->addRegistrationTokenSender($sender);
        }

        foreach ($senderPlugins[CustomerDependencyProvider::PASSWORD_RESTORE_TOKEN_SENDERS] as $sender) {
            $customer->addPasswordRestoreTokenSender($sender);
        }

        foreach ($senderPlugins[CustomerDependencyProvider::PASSWORD_RESTORED_CONFIRMATION_SENDERS] as $sender) {
            $customer->addPasswordRestoredConfirmationSender($sender);
        }

        return $customer;
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Customer\Address
     */
    public function createAddress()
    {
        return new Address($this->getQueryContainer(), $this->getCountryFacade(), $this->getLocaleFacade());
    }

    /**
     * @deprecated Use getCountryFacade() instead.
     *
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface
     */
    protected function createCountryFacade()
    {
        trigger_error('Deprecated, use getCountryFacade() instead.', E_USER_DEPRECATED);

        return $this->getCountryFacade();
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface
     */
    protected function getCountryFacade()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @deprecated Use getLocaleFacade() instead.
     *
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface
     */
    protected function createLocaleFacade()
    {
        trigger_error('Deprecated, use getLocaleFacade() instead.', E_USER_DEPRECATED);

        return $this->getLocaleFacade();
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGenerator
     */
    protected function createCustomerReferenceGenerator()
    {
        return new CustomerReferenceGenerator(
            $this->getSequenceNumberFacade(),
            $this->getConfig()->getCustomerReferenceDefaults()
        );
    }

    /**
     * @deprecated Use getSequenceNumberFacade() instead.
     *
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToSequenceNumberInterface
     */
    protected function createSequenceNumberFacade()
    {
        trigger_error('Deprecated, use getSequenceNumberFacade() instead.', E_USER_DEPRECATED);

        return $this->getSequenceNumberFacade();
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToSequenceNumberInterface
     */
    protected function getSequenceNumberFacade()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

}
