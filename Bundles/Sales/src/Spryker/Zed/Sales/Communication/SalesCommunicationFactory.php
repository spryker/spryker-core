<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication;

use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Communication\Form\OrderItemSplitForm;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\Sales\Communication\Table\OrdersTable;
use Spryker\Zed\Sales\Communication\Form\CustomerForm;
use Spryker\Zed\Sales\Communication\Form\AddressForm;
use Spryker\Zed\Sales\SalesConfig;

/**
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 */
class SalesCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return Form\OrderItemSplitForm
     */
    public function createOrderItemSplitForm()
    {
        $form = new OrderItemSplitForm();

        return $this->createForm($form);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Spryker\Zed\Sales\Communication\Form\CustomerForm
     */
    public function createCustomerForm($idSalesOrder)
    {
        $customerQuery = $this->getQueryContainer()->querySalesOrderById($idSalesOrder);

        $form = new CustomerForm($customerQuery);

        return $this->createForm($form);
    }

    /**
     * @param int $idOrderAddress
     *
     * @return \Spryker\Zed\Sales\Communication\Form\AddressForm
     */
    public function createAddressForm($idOrderAddress)
    {
        $addressQuery = $this->getQueryContainer()->querySalesOrderAddressById($idOrderAddress);

        $form = new AddressForm($addressQuery);

        return $this->createForm($form);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     *
     * @return array
     */
    public function createOrderItemSplitFormCollection(ObjectCollection $orderItems)
    {
        $formCollectionArray = [];

        foreach ($orderItems as $item) {
            $form = new OrderItemSplitForm($item);
            $formCollectionArray[$item->getIdSalesOrderItem()] = $this->createForm($form, [
                'action' => '/sales/order-item-split/split',
            ])->createView();
        }

        return $formCollectionArray;
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
