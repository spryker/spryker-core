<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Sales\Communication\Form\AddressForm;
use Spryker\Zed\Sales\Communication\Form\CommentForm;
use Spryker\Zed\Sales\Communication\Form\CustomerForm;
use Spryker\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider;
use Spryker\Zed\Sales\Communication\Form\DataProvider\CommentFormDataProvider;
use Spryker\Zed\Sales\Communication\Form\DataProvider\CustomerFormDataProvider;
use Spryker\Zed\Sales\Communication\Table\OrdersTable;
use Spryker\Zed\Sales\SalesDependencyProvider;

/**
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 */
class SalesCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerForm(array $formData = [], array $formOptions = [])
    {
        $customerFormType = new CustomerForm();

        return $this->getFormFactory()->create($customerFormType, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Form\DataProvider\CustomerFormDataProvider
     */
    public function createCustomerFormDataProvider()
    {
        return new CustomerFormDataProvider($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Form\DataProvider\CommentFormDataProvider
     */
    public function createCommentFormDataProvider()
    {
        return new CommentFormDataProvider();
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAddressForm(array $formData = [], array $formOptions = [])
    {
        $addressFormType = new AddressForm();

        return $this->getFormFactory()->create($addressFormType, $formData, $formOptions);
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCommentForm(array $formData = [], array $formOptions = [])
    {
        return $this->getFormFactory()->create(new CommentForm(), $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider
     */
    public function createAddressFormDataProvider()
    {
        return new AddressFormDataProvider($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Table\OrdersTable
     */
    public function createOrdersTable()
    {
        $orderQuery = $this->getQueryContainer()->querySalesOrder();
        $orderItemQuery = $this->getQueryContainer()->querySalesOrderItem();
        return new OrdersTable(
            $orderQuery,
            $orderItemQuery,
            $this->getSalesAggregator()
        );
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
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface
     */
    public function getSalesAggregator()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_SALES_AGGREGATOR);
    }

    /**
     * @return array
     */
    public function getSalesDetailExternalBlocksUrls()
    {
        return $this->getConfig()->getSalesDetailExternalBlocksUrls();
    }

}
