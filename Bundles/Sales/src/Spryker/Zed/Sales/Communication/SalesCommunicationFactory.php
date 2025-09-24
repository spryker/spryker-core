<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication;

use ArrayObject;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Sales\Communication\Form\AddressForm;
use Spryker\Zed\Sales\Communication\Form\CommentForm;
use Spryker\Zed\Sales\Communication\Form\CustomerForm;
use Spryker\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider;
use Spryker\Zed\Sales\Communication\Form\DataProvider\CommentFormDataProvider;
use Spryker\Zed\Sales\Communication\Form\DataProvider\CustomerFormDataProvider;
use Spryker\Zed\Sales\Communication\Form\DataProvider\TableFilterFormDataProvider;
use Spryker\Zed\Sales\Communication\Form\TableFilterForm;
use Spryker\Zed\Sales\Communication\Table\CustomerOrdersTable;
use Spryker\Zed\Sales\Communication\Table\OrdersTable;
use Spryker\Zed\Sales\Communication\Table\OrdersTableQueryBuilder;
use Spryker\Zed\Sales\Communication\TableExpander\OrderItemsTableExpander;
use Spryker\Zed\Sales\Communication\TableExpander\OrderItemsTableExpanderInterface;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesSplit\Communication\Form\DataProvider\OrderItemSplitDataProvider;
use Spryker\Zed\SalesSplit\Communication\Form\OrderItemSplitForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 * @method \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 */
class SalesCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @deprecated Use {@link getCustomerForm()} instead.
     *
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerForm(array $formData = [], array $formOptions = [])
    {
        return $this->getFormFactory()->create(CustomerForm::class, $formData, $formOptions);
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCustomerForm(array $formData = [], array $formOptions = [])
    {
        return $this->createCustomerForm($formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Form\DataProvider\CustomerFormDataProvider
     */
    public function createCustomerFormDataProvider()
    {
        return new CustomerFormDataProvider($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\TableExpander\OrderItemsTableExpanderInterface
     */
    public function createOrderItemsTableExpander(): OrderItemsTableExpanderInterface
    {
        return new OrderItemsTableExpander($this->getOrderItemsTableExpanderPlugins());
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Form\DataProvider\CommentFormDataProvider
     */
    public function createCommentFormDataProvider()
    {
        return new CommentFormDataProvider();
    }

    /**
     * @deprecated Use {@link getAddressForm()} instead.
     *
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
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAddressForm(array $formData = [], array $formOptions = [])
    {
        return $this->createAddressForm($formData, $formOptions);
    }

    /**
     * @deprecated Use {@link getCommentForm()} instead.
     *
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCommentForm(array $formData = [], array $formOptions = [])
    {
        return $this->getFormFactory()->create(CommentForm::class, $formData, $formOptions);
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCommentForm(array $formData = [], array $formOptions = [])
    {
        return $this->createCommentForm($formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider
     */
    public function createAddressFormDataProvider()
    {
        return new AddressFormDataProvider(
            $this->getQueryContainer(),
            $this->getCountryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Table\OrdersTable
     */
    public function createOrdersTable()
    {
        return new OrdersTable(
            $this->createOrdersTableQueryBuilder(),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_MONEY),
            $this->getProvidedDependency(SalesDependencyProvider::SERVICE_UTIL_SANITIZE),
            $this->getUtilDateTimeService(),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_CUSTOMER),
            $this->getSalesTablePlugins(),
        );
    }

    /**
     * @param string $customerReference
     *
     * @return \Spryker\Zed\Sales\Communication\Table\CustomerOrdersTable
     */
    public function createCustomerOrdersTable($customerReference)
    {
        return new CustomerOrdersTable(
            $this->createOrdersTableQueryBuilder(),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_MONEY),
            $this->getProvidedDependency(SalesDependencyProvider::SERVICE_UTIL_SANITIZE),
            $this->getUtilDateTimeService(),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_CUSTOMER),
            $customerReference,
            $this->getQueryContainer(),
            $this->getSalesTablePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Table\OrdersTableQueryBuilderInterface
     */
    protected function createOrdersTableQueryBuilder()
    {
        return new OrdersTableQueryBuilder($this->getQueryContainer()->querySalesOrder());
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $orderItems
     *
     * @return array
     */
    public function createOrderItemSplitFormCollection(ArrayObject $orderItems)
    {
        $formCollection = [];
        $orderItemSplitDataProvider = $this->createOrderItemSplitDataProvider();
        foreach ($orderItems as $itemTransfer) {
            $formCollection[$itemTransfer->getIdSalesOrderItem()] = $this
                ->getFormFactory()
                ->create(OrderItemSplitForm::class, $orderItemSplitDataProvider->getData($itemTransfer), $orderItemSplitDataProvider->getOptions())
                ->createView();
        }

        return $formCollection;
    }

    /**
     * @return \Spryker\Zed\SalesSplit\Communication\Form\DataProvider\OrderItemSplitDataProvider
     */
    public function createOrderItemSplitDataProvider()
    {
        return new OrderItemSplitDataProvider();
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    public function getOmsFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToUserInterface
     */
    public function getUserFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface
     */
    public function getCountryFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface
     */
    public function getStoreFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToTranslatorInterface
     */
    public function getTranslatorFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return array
     */
    public function getSalesDetailExternalBlocksUrls()
    {
        return $this->getConfig()->getSalesDetailExternalBlocksUrls();
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesTablePluginInterface>
     */
    public function getSalesTablePlugins()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::UI_SALES_TABLE_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsTableExpanderPluginInterface>
     */
    public function getOrderItemsTableExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SalesDependencyProvider::PLUGINS_ORDER_ITEMS_TABLE_EXPANDER);
    }

    /**
     * @return \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    public function getCsrfTokenManager(): CsrfTokenManagerInterface
    {
        return $this->getProvidedDependency(SalesDependencyProvider::SERVICE_FORM_CSRF_PROVIDER);
    }

    /**
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): UtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SalesDependencyProvider::SERVICE_DATE_FORMATTER);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTableFilterForm(): FormInterface
    {
        $dataProvider = $this->createTableFilterFormDataProvider();

        return $this->getFormFactory()->create(
            TableFilterForm::class,
            $dataProvider->getData(),
            $dataProvider->getOptions(),
        );
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Form\DataProvider\TableFilterFormDataProvider
     */
    protected function createTableFilterFormDataProvider(): TableFilterFormDataProvider
    {
        return new TableFilterFormDataProvider(
            $this->getQueryContainer(),
            $this->getStoreFacade(),
            $this->getRepository(),
            $this->getUtilDateTimeService(),
        );
    }
}
