<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business;

use Spryker\Zed\Customer\Business\Anonymizer\CustomerAnonymizer;
use Spryker\Zed\Customer\Business\Customer\Address;
use Spryker\Zed\Customer\Business\Customer\Customer;
use Spryker\Zed\Customer\Business\Customer\EmailValidator;
use Spryker\Zed\Customer\Business\Model\CustomerOrderSaver;
use Spryker\Zed\Customer\Business\Model\PreConditionChecker;
use Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGenerator;
use Spryker\Zed\Customer\Business\Sales\CustomerOrderHydrator;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Customer\CustomerConfig getConfig()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainer getQueryContainer()
 */
class CustomerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Customer\Business\Customer\CustomerInterface
     */
    public function createCustomer()
    {
        $config = $this->getConfig();

        $customer = new Customer(
            $this->getQueryContainer(),
            $this->createCustomerReferenceGenerator(),
            $config,
            $this->createEmailValidator(),
            $this->getMailFacade(),
            $this->getLocaleQueryContainer(),
            $this->getStore()
        );

        return $customer;
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Customer\AddressInterface
     */
    public function createAddress()
    {
        return new Address($this->getQueryContainer(), $this->getCountryFacade(), $this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface
     */
    protected function getCountryFacade()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface
     */
    protected function getMailFacade()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGeneratorInterface
     */
    protected function createCustomerReferenceGenerator()
    {
        return new CustomerReferenceGenerator(
            $this->getSequenceNumberFacade(),
            $this->getConfig()->getCustomerReferenceDefaults()
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToSequenceNumberInterface
     */
    protected function getSequenceNumberFacade()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Model\CustomerOrderSaverInterface
     */
    public function createCustomerOrderSaver()
    {
        return new CustomerOrderSaver($this->createCustomer(), $this->createAddress());
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Model\PreConditionCheckerInterface
     */
    public function createPreConditionChecker()
    {
        return new PreConditionChecker($this->createCustomer());
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Anonymizer\CustomerAnonymizer
     */
    public function createCustomerAnonymizer()
    {
        return new CustomerAnonymizer(
            $this->getQueryContainer(),
            $this->createCustomer(),
            $this->createAddress(),
            $this->getCustomerAnonymizerPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Plugin\CustomerAnonymizerPluginInterface[]
     */
    public function getCustomerAnonymizerPlugins()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_CUSTOMER_ANONYMIZER);
    }

    /**
     * @return \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface
     */
    protected function getLocaleQueryContainer()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::QUERY_CONTAINER_LOCALE);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Sales\CustomerOrderHydratorInterface
     */
    public function createCustomerOrderHydrator()
    {
        return new CustomerOrderHydrator(
            $this->createCustomer()
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateInterface
     */
    protected function getUtilValidateService()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_UTIL_VALIDATE);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Customer\EmailValidator
     */
    protected function createEmailValidator()
    {
        return new EmailValidator(
            $this->getQueryContainer(),
            $this->getUtilValidateService()
        );
    }
}
