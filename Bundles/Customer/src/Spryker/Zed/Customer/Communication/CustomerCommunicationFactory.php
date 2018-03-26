<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication;

use Spryker\Zed\Customer\Communication\Form\AddressForm;
use Spryker\Zed\Customer\Communication\Form\CustomerForm;
use Spryker\Zed\Customer\Communication\Form\CustomerUpdateForm;
use Spryker\Zed\Customer\Communication\Form\DataProvider\AddressFormDataProvider;
use Spryker\Zed\Customer\Communication\Form\DataProvider\CustomerFormDataProvider;
use Spryker\Zed\Customer\Communication\Form\DataProvider\CustomerUpdateFormDataProvider;
use Spryker\Zed\Customer\Communication\Table\AddressTable;
use Spryker\Zed\Customer\Communication\Table\CustomerTable;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\CustomerConfig getConfig()
 */
class CustomerCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Customer\Communication\Table\CustomerTable
     */
    public function createCustomerTable()
    {
        return new CustomerTable(
            $this->getQueryContainer(),
            $this->getUtilDateTimeService()
        );
    }

    /**
     * @param int $idCustomer
     *
     * @return \Spryker\Zed\Customer\Communication\Table\AddressTable
     */
    public function createCustomerAddressTable($idCustomer)
    {
        return new AddressTable($this->getQueryContainer(), $idCustomer, $this->getUtilSanitizeService());
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create(CustomerForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Customer\Communication\Form\DataProvider\CustomerFormDataProvider
     */
    public function createCustomerFormDataProvider()
    {
        return new CustomerFormDataProvider($this->getQueryContainer(), $this->getLocaleFacade());
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerUpdateForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create(CustomerUpdateForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Customer\Communication\Form\DataProvider\CustomerUpdateFormDataProvider
     */
    public function createCustomerUpdateFormDataProvider()
    {
        return new CustomerUpdateFormDataProvider($this->getQueryContainer(), $this->getLocaleFacade());
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAddressForm(array $formData = [], array $formOptions = [])
    {
        return $this->getFormFactory()->create(AddressForm::class, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Customer\Communication\Form\DataProvider\AddressFormDataProvider
     */
    public function createAddressFormDataProvider()
    {
        return new AddressFormDataProvider(
            $this->getProvidedDependency(CustomerDependencyProvider::FACADE_COUNTRY),
            $this->getQueryContainer(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::STORE);
    }

    /**
     * @deprecated Please use `getLocaleFacadePublic()` instead.
     *
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getLocaleFacadePublic();
    }

    /**
     * Deprecated: This will be renamed to `getLocaleFacade()` in the next major.
     *
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface
     */
    public function getLocaleFacadePublic()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilSanitizeServiceInterface
     */
    protected function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\Application\Business\Model\Request\SubRequestHandlerInterface
     */
    public function getSubRequestHandler()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::SUB_REQUEST_HANDLER);
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilDateTimeServiceInterface
     */
    protected function getUtilDateTimeService()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return array
     */
    public function getCustomerDetailExternalBlocksUrls()
    {
        return $this->getConfig()->getCustomerDetailExternalBlocksUrls();
    }
}
