<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Address\AddressCollectionType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\ProductDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomersListType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomerType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\AddressCollectionDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomerDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomersListDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product\ProductsCollectionType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Service\ProductMapper;
use Spryker\Zed\ManualOrderEntryGui\Communication\Service\StepEngine;
use Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiDependencyProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiConfig getConfig()
 */
class ManualOrderEntryGuiCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCustomerFacadeInterface
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getCustomerFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeInterface
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * // @todo change on bridge
     * @return \Spryker\Zed\Cart\Business\CartFacade
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getCartFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_CART);
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[]
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getManualOrderEntryFormPlugins(Request $request, $quoteTransfer)
    {
        $formPlugins = $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::PLUGINS_MANUAL_ORDER_ENTRY_FORM);

        return $this->createStepEngine()->filterFormPlugins($formPlugins, $request, $quoteTransfer);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Service\StepEngine
     */
    public function createStepEngine()
    {
        return new StepEngine();
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Service\ProductMapper
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createProductMapper()
    {
        return new ProductMapper(
            $this->getProductFacade(),
            $this->getCartFacade()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomersListDataProvider
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createCustomersListDataProvider(Request $request)
    {
        return new CustomersListDataProvider(
            $this->getCustomerQueryContainer(),
            $request
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createCustomersListForm(Request $request, $quoteTransfer)
    {
        $formDataProvider = $this->createCustomersListDataProvider($request);

        return $this->getFormFactory()->create(
            CustomersListType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions()
        );
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
     * @return \Symfony\Component\Form\FormInterface
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createAddressCollectionForm()
    {
        $formDataProvider = $this->createAddressCollectionDataProvider();

        return $this->getFormFactory()->create(
            AddressCollectionType::class,
            $formDataProvider->getData(new QuoteTransfer()),
            $formDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\ProductDataProvider
     */
    public function createProductCollectionDataProvider()
    {
        return new ProductDataProvider();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductsCollectionForm()
    {
        $formDataProvider = $this->createProductCollectionDataProvider();

        return $this->getFormFactory()->create(
            ProductsCollectionType::class,
            $formDataProvider->getData(new QuoteTransfer()),
            $formDataProvider->getOptions()
        );
    }

}
