<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CustomerBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Customer\Business\Customer\Customer;
use SprykerFeature\Zed\Customer\Business\Customer\Address;
use SprykerFeature\Zed\Customer\CustomerConfig;
use SprykerFeature\Zed\Customer\CustomerDependencyProvider;
use SprykerFeature\Zed\Customer\Dependency\Facade\CustomerToCountryInterface;
use SprykerFeature\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;

/**
 * @method CustomerConfig getConfig()
 * @method CustomerBusiness getFactory()
 */
class CustomerDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return CustomerQueryContainerInterface
     */
    public function createQueryContainer()
    {
        return $this->getLocator()->customer()->queryContainer();
    }

    /**
     * @return Customer
     */
    public function createCustomer()
    {
        $config = $this->getConfig();
        $senderPlugins = $this->getProvidedDependency(CustomerDependencyProvider::SENDER_PLUGINS);
        $customer = $this->getFactory()->createCustomerCustomer(
            $this->createQueryContainer(),
            $config->getHostYves()
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
     * @return Address
     */
    public function createAddress()
    {
        return $this->getFactory()->createCustomerAddress(
            $this->createQueryContainer(),
            $this->createCountryFacade(),
            $this->createLocaleFacade()
        );
    }

    /**
     * @return CustomerToCountryInterface
     */
    protected function createCountryFacade()
    {
        return $this->getLocator()->country()->facade();
    }

    /**
     * @return CustomerToLocaleInterface
     */
    protected function createLocaleFacade()
    {
        return $this->getLocator()->locale()->facade();
    }

}
