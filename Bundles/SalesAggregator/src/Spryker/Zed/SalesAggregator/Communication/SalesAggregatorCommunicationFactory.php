<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator\Communication;

use ArrayObject;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesAggregator\Business\SalesAggregatorFacade;
use Spryker\Zed\SalesAggregator\Communication\Form\AddressForm;
use Spryker\Zed\SalesAggregator\Communication\Form\CommentForm;
use Spryker\Zed\SalesAggregator\Communication\Form\CustomerForm;
use Spryker\Zed\SalesAggregator\Communication\Form\DataProvider\AddressFormDataProvider;
use Spryker\Zed\SalesAggregator\Communication\Form\DataProvider\CommentFormDataProvider;
use Spryker\Zed\SalesAggregator\Communication\Form\DataProvider\CustomerFormDataProvider;
use Spryker\Zed\SalesAggregator\Communication\Form\DataProvider\OrderItemSplitDataProvider;
use Spryker\Zed\SalesAggregator\Communication\Form\OrderItemSplitForm;
use Spryker\Zed\SalesAggregator\Communication\Table\OrdersTable;
use Spryker\Zed\SalesAggregator\SalesAggregatorDependencyProvider;

/**
 * @method \Spryker\Zed\SalesAggregator\Persistence\SalesAggregatorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesAggregator\SalesAggregatorConfig getConfig()
 */
class SalesAggregatorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createOrderItemSplitForm()
    {
        $formType = new OrderItemSplitForm();

        return $this->getFormFactory()->create($formType);
    }

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
     * @return \Spryker\Zed\SalesAggregator\Communication\Form\DataProvider\CustomerFormDataProvider
     */
    public function createCustomerFormDataProvider()
    {
        return new CustomerFormDataProvider($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Communication\Form\DataProvider\CommentFormDataProvider
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
     * @return \Spryker\Zed\SalesAggregator\Communication\Form\DataProvider\AddressFormDataProvider
     */
    public function createAddressFormDataProvider()
    {
        return new AddressFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return array
     */
    public function createOrderItemSplitFormCollection(ArrayObject $orderItems)
    {
        $formCollectionArray = [];
        $orderItemSplitDataProvider = $this->createOrderItemSplitDataProvider();
        foreach ($orderItems as $itemTransfer) {
            $formType = new OrderItemSplitForm();
            $formCollectionArray[$itemTransfer->getIdSalesAggregatorOrderItem()] = $this
                ->getFormFactory()
                ->create($formType, $orderItemSplitDataProvider->getData($itemTransfer), $orderItemSplitDataProvider->getOptions())
                ->createView();
        }

        return $formCollectionArray;
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Communication\Form\DataProvider\OrderItemSplitDataProvider
     */
    public function createOrderItemSplitDataProvider()
    {
        return new OrderItemSplitDataProvider();
    }

    /**
     * @param \Spryker\Zed\SalesAggregator\Business\SalesAggregatorFacade $SalesAggregatorFacade
     *
     * @return \Spryker\Zed\SalesAggregator\Communication\Table\OrdersTable
     */
    public function createOrdersTable(SalesAggregatorFacade $SalesAggregatorFacade)
    {
        $orderQuery = $this->getQueryContainer()->querySalesAggregatorOrder();
        $orderItemQuery = $this->getQueryContainer()->querySalesAggregatorOrderItem();

        return new OrdersTable($orderQuery, $orderItemQuery, $SalesAggregatorFacade);
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToOmsInterface
     */
    public function getOmsFacade()
    {
        return $this->getProvidedDependency(SalesAggregatorDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToUserInterface
     */
    public function getUserFacade()
    {
        return $this->getProvidedDependency(SalesAggregatorDependencyProvider::FACADE_USER);
    }

}
