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
            $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_DATE_FORMATTER)
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
        $customerFormType = new CustomerForm($this->getQueryContainer());

        return $this->getFormFactory()->create($customerFormType, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Customer\Communication\Form\DataProvider\CustomerFormDataProvider
     */
    public function createCustomerFormDataProvider()
    {
        return new CustomerFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerUpdateForm(array $data = [], array $options = [])
    {
        $customerFormType = new CustomerUpdateForm($this->getQueryContainer());

        return $this->getFormFactory()->create($customerFormType, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Customer\Communication\Form\DataProvider\CustomerUpdateFormDataProvider
     */
    public function createCustomerUpdateFormDataProvider()
    {
        return new CustomerUpdateFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAddressForm(array $formData = [], array $formOptions = [])
    {
        $customerAddressFormType = new AddressForm();

        return $this->getFormFactory()->create($customerAddressFormType, $formData, $formOptions);
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
     * @return \Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface[]
     */
    public function getCustomerTransferExpanderPlugins()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_CUSTOMER_TRANSFER_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilSanitizeInterface
     */
    protected function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_UTIL_SANITIZE);
    }
}
