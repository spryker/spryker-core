<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesCommunication;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Sales\Communication\Form\OrderItemSplitForm\Collection;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
use SprykerFeature\Zed\Sales\SalesDependencyProvider;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Communication\Table\OrdersTable;
use SprykerFeature\Zed\Sales\Communication\Form\CustomerForm;
use SprykerFeature\Zed\Sales\Communication\Form\AddressForm;

/**
 * @method SalesCommunication getFactory()
 * @method SalesQueryContainerInterface getQueryContainer()
 */
class SalesDependencyContainer extends AbstractCommunicationDependencyContainer
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
        return $this->getFactory()->createFormOrderItemSplitForm();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return CustomerForm
     */
    public function createCustomerForm($idSalesOrder)
    {
        $customerQuery = $this->getQueryContainer()->querySalesOrderById($idSalesOrder);

        return $this->getFactory()->createFormCustomerForm($customerQuery);
    }

    /**
     * @param int $idOrderAddress
     *
     * @return AddressForm
     */
    public function createAddressForm($idOrderAddress)
    {
        $addressQuery = $this->getQueryContainer()->querySalesOrderAddressById($idOrderAddress);

        return $this->getFactory()->createFormAddressForm($addressQuery);
    }

    /**
     * @param ObjectCollection $orderItems
     *
     * @return Collection
     */
    public function getOrderItemSplitFormCollection(ObjectCollection $orderItems)
    {
        return $this->getFactory()->createFormOrderItemSplitFormCollection($orderItems);
    }

    /**
     * @return OrdersTable
     */
    public function createOrdersTable()
    {
        $orderQuery = $this->getQueryContainer()->querySalesOrder();
        $orderItemQuery = $this->getQueryContainer()->querySalesOrderItem();

        return $this->getFactory()->createTableOrdersTable($orderQuery, $orderItemQuery);
    }

    /**
     * @return OmsFacade
     */
    public function getOmsFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS);
    }

}
