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
use SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin\RegistrationTokenSender;

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
        $customer = $this->getFactory()->createCustomerCustomer(
            $this->createQueryContainer()
        );

        $config = $this->getConfig();

        foreach ($config->getPasswordRestoredConfirmationSenders() as $senderClassName) {
            $customer->addPasswordRestoredConfirmationSender($this->createSender($senderClassName));
        }

        foreach ($config->getPasswordRestoreTokenSenders() as $senderClassName) {
            $customer->addPasswordRestoreTokenSender($this->createSender($senderClassName));
        }

        foreach ($config->getRegistrationTokenSenders() as $senderClassName) {
            $customer->addRegistrationTokenSender($this->createSender($senderClassName));
        }

        return $customer;
    }

    /**
     * @param $senderClassName
     *
     * @throws \ErrorException
     * @return mixed
     */
    public function createSender($senderClassName) {
        return $this->getProvidedDependency($senderClassName);
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
