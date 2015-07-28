<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesCommunication;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
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
     * @return mixed
     */
    public function getCommentForm()
    {
        return $this->getFactory()->createFormCommentForm(
            null,
            $this->getQueryContainer()
        );
    }
    /**
     * @return Form\OrderItemSplitForm
     */
    public function getOrderItemSplitForm()
    {
        return $this->getFactory()->createFormOrderItemSplitForm();
    }

    /**
     * @return mixed
     */
    public function getDemoCommentForm()
    {
        return $this->getFactory()->createFormDemoCommentForm(
            null,
            $this->getQueryContainer()
        );
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
     * @param Request $request
     *
     * @return CommentsGrid
     */
    public function getCommentsGridByOrderId(Request $request)
    {
        return $this->getFactory()->createGridCommentsGrid(
            $this->getQueryContainer()->queryCommentsByOrderId($request->get('orderId'))
        );
    }

    /**
     * @return SalesGrid
     */
    public function getSalesGrid()
    {
        $salesQuery = $this->getQueryContainer()->querySalesOrder();

        return $this->getFactory()->createGridSalesGrid($salesQuery);
    }

    /**
     * @param int $idOrder
     *
     * @return OrderItemsGrid
     */
    public function getOrdersItemsGridByOrderId($idOrder)
    {
        return $this->getFactory()->createGridOrderItemsGrid(
            $this->getQueryContainer()->querySalesOrderItemsByIdSalesOrder($idOrder));
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

        return $this->getFactory()->createTableOrdersTable($orderQuery);
    }

}
