<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication;

use Spryker\Zed\Sales\Communication\Form\OrderItemSplitForm;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Oms\Business\OmsFacade;
use Spryker\Zed\Sales\Communication\Form\OrderItemSplitForm\Collection;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\Sales\Communication\Table\OrdersTable;
use Spryker\Zed\Sales\Communication\Form\CustomerForm;
use Spryker\Zed\Sales\Communication\Form\AddressForm;

/**
 * @method SalesQueryContainerInterface getQueryContainer()
 */
class SalesCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @Todo check if we can remove this method
     *
     * @return mixed
     */
    public function getCommentForm()
    {
    }

    /**
     * @return Form\OrderItemSplitForm
     */
    public function getOrderItemSplitForm()
    {
        return new OrderItemSplitForm();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return CustomerForm
     */
    public function createCustomerForm($idSalesOrder)
    {
        $customerQuery = $this->getQueryContainer()->querySalesOrderById($idSalesOrder);

        return new CustomerForm($customerQuery);
    }

    /**
     * @param int $idOrderAddress
     *
     * @return AddressForm
     */
    public function createAddressForm($idOrderAddress)
    {
        $addressQuery = $this->getQueryContainer()->querySalesOrderAddressById($idOrderAddress);

        return new AddressForm($addressQuery);
    }

    /**
     * @param ObjectCollection $orderItems
     *
     * @return Collection
     */
    public function getOrderItemSplitFormCollection(ObjectCollection $orderItems)
    {
        return new Collection($orderItems);
    }

    /**
     * @return OrdersTable
     */
    public function createOrdersTable()
    {
        $orderQuery = $this->getQueryContainer()->querySalesOrder();
        $orderItemQuery = $this->getQueryContainer()->querySalesOrderItem();

        return new OrdersTable($orderQuery, $orderItemQuery);
    }

    /**
     * @return OmsFacade
     */
    public function getOmsFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS);
    }

}
