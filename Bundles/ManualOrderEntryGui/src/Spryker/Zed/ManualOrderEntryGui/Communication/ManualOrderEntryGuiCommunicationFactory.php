<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication;

use Pyz\Yves\Customer\Form\RegisterForm;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Address\AddressCollectionType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Address\AddressType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Checkout\CheckoutForm;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomersListType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomerType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\AddressCollectionDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\AddressDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CheckoutFormDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomerDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomersListDataProvider;
use Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiDependencyProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiConfig getConfig()
 */
class ManualOrderEntryGuiCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CheckoutFormDataProvider
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createCheckoutFormDataProvider(Request $request)
    {
        return new CheckoutFormDataProvider(
            $this->getCustomerQueryContainer(),
            $request
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacade
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getCustomerFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerInterface
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getCustomerQueryContainer()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::QUERY_CONTAINER_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Service\ManualOrderEntryGuiToStoreInterface
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::STORE);
    }

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CheckoutFormDataProvider $checkoutFormDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCheckoutForm(CheckoutFormDataProvider $checkoutFormDataProvider)
    {
        return $this->getFormFactory()->create(
            CheckoutForm::class,
            $checkoutFormDataProvider->getData(),
            $checkoutFormDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\CheckoutFormPluginInterface[]
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getCheckoutFormPlugins()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::PLUGINS_CHECKOUT_FORM);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomersListType
     */
    public function createCustomersListType()
    {
        return new CustomersListType();
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomersListDataProvider
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createCustomersListDataProvider()
    {
        return new CustomersListDataProvider(
            $this->getCustomerQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomerType
     */
    public function createCustomerType()
    {
        return new CustomerType();
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomerDataProvider
     */
    public function createCustomerDataProvider()
    {
        return new CustomerDataProvider();
    }

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomerDataProvider $customerFormDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerForm(CustomerDataProvider $customerFormDataProvider)
    {
        return $this->getFormFactory()->create(
            CustomerType::class,
           null,
            $customerFormDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\Address\AddressCollectionType
     */
    public function createAddressCollectionType()
    {
        return new AddressCollectionType();
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\AddressCollectionDataProvider
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createAddressCollectionDataProvider()
    {
        return new AddressCollectionDataProvider($this->getStore());
    }

    /**
     * @todo @Artem delete
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\Address\AddressType
     */
    public function createAddressType()
    {
        return new AddressType();
    }

    /**
     * @todo @Artem delete
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\AddressDataProvider
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createAddressDataProvider()
    {
        return new AddressDataProvider($this->getStore());
    }

}
