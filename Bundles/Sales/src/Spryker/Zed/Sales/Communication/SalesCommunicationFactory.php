<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication;

use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Communication\Form\OrderItemSplitForm;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\Sales\Communication\Table\OrdersTable;
use Spryker\Zed\Sales\Communication\Form\CustomerForm;
use Spryker\Zed\Sales\Communication\Form\AddressForm;
use Spryker\Zed\Sales\Communication\Form\DataProvider\CustomerFormDataProvider;
use Spryker\Zed\Sales\Communication\Form\DataProvider\OrderItemSplitDataProvider;
use Spryker\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider;


/**
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 */
class SalesCommunicationFactory extends AbstractCommunicationFactory
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
     * @return \Spryker\Zed\Sales\Communication\Form\DataProvider\CustomerFormDataProvider
     */
    public function createCustomerFormDataProvider()
    {
        return new CustomerFormDataProvider($this->getQueryContainer());
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
     * @return \Spryker\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider
     */
    public function createAddressFormDataProvider()
    {
        return new AddressFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     *
     * @return array
     */
    public function createOrderItemSplitFormCollection(ObjectCollection $orderItems)
    {
        $formCollectionArray = [];
        $orderItemSplitDataProvider = $this->createOrderItemSplitDataProvider();
        foreach ($orderItems as $item) {
            $formType = new OrderItemSplitForm();
            $formCollectionArray[$item->getIdSalesOrderItem()] = $this
                ->getFormFactory()
                ->create($formType, $orderItemSplitDataProvider->getData($item), $orderItemSplitDataProvider->getOptions())
                ->createView();
        }
        return $formCollectionArray;
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Form\DataProvider\OrderItemSplitDataProvider
     */
    public function createOrderItemSplitDataProvider()
    {
        return new OrderItemSplitDataProvider();
    }


    /**
     * @param \Spryker\Zed\Sales\Business\SalesFacade $salesFacade
     *
     * @return \Spryker\Zed\Sales\Communication\Table\OrdersTable
     */
    public function createOrdersTable(SalesFacade $salesFacade)
    {
        $orderQuery = $this->getQueryContainer()->querySalesOrder();
        $orderItemQuery = $this->getQueryContainer()->querySalesOrderItem();

        return new OrdersTable($orderQuery, $orderItemQuery, $salesFacade);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    public function getOmsFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS);
    }

}
