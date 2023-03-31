<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication;

use Spryker\Zed\Customer\Communication\Form\AddressForm;
use Spryker\Zed\Customer\Communication\Form\CustomerDeleteForm;
use Spryker\Zed\Customer\Communication\Form\CustomerForm;
use Spryker\Zed\Customer\Communication\Form\CustomerUpdateForm;
use Spryker\Zed\Customer\Communication\Form\DataProvider\AddressFormDataProvider;
use Spryker\Zed\Customer\Communication\Form\DataProvider\CustomerFormDataProvider;
use Spryker\Zed\Customer\Communication\Form\DataProvider\CustomerUpdateFormDataProvider;
use Spryker\Zed\Customer\Communication\Table\AddressTable;
use Spryker\Zed\Customer\Communication\Table\CustomerTable;
use Spryker\Zed\Customer\Communication\Table\PluginExecutor\CustomerTableExpanderPluginExecutor;
use Spryker\Zed\Customer\Communication\Table\PluginExecutor\CustomerTableExpanderPluginExecutorInterface;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToRouterFacadeInterface;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\CustomerConfig getConfig()
 * @method \Spryker\Zed\Customer\Persistence\CustomerEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
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
            $this->getUtilDateTimeService(),
            $this->createCustomerTableActionPluginExecutor(),
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
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create(CustomerForm::class, $data, $options);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCustomerDeleteForm(): FormInterface
    {
        return $this->getFormFactory()->create(CustomerDeleteForm::class);
    }

    /**
     * @return \Spryker\Zed\Customer\Communication\Form\DataProvider\CustomerFormDataProvider
     */
    public function createCustomerFormDataProvider()
    {
        return new CustomerFormDataProvider(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
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
        return new CustomerUpdateFormDataProvider(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->getStoreFacade(),
        );
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
            $this->getCountryFacade(),
            $this->getQueryContainer(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface
     */
    public function getLocaleFacade()
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

    /**
     * @return array<\Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerTableActionExpanderPluginInterface>
     */
    public function getCustomerTableActionExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_CUSTOMER_TABLE_ACTION_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\Customer\Communication\Table\PluginExecutor\CustomerTableExpanderPluginExecutorInterface
     */
    public function createCustomerTableActionPluginExecutor(): CustomerTableExpanderPluginExecutorInterface
    {
        return new CustomerTableExpanderPluginExecutor(
            $this->getCustomerTableActionExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface
     */
    public function getCountryFacade(): CustomerToCountryInterface
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface
     */
    public function getStoreFacade(): CustomerToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToRouterFacadeInterface
     */
    public function getRouterFacade(): CustomerToRouterFacadeInterface
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::FACADE_ROUTER);
    }
}
