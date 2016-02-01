<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication;

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
use Symfony\Component\Form\FormInterface;

/**
 * @method SalesQueryContainerInterface getQueryContainer()
 * @method SalesConfig getConfig()
 */
class SalesCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createOrderItemSplitForm()
    {
        $form = new OrderItemSplitForm();

        return $this->createForm($form);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Symfony\Component\Form\FormInterface
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
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAddressForm($idOrderAddress)
    {
        $addressQuery = $this->getQueryContainer()->querySalesOrderAddressById($idOrderAddress);

        $form = new AddressForm($addressQuery);

        return $this->createForm($form);
    }

    /**
     * @param ObjectCollection $orderItems
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
     * @return \Spryker\Zed\Sales\Communication\Table\OrdersTable
     */
    public function createOrdersTable()
    {
        $orderQuery = $this->getQueryContainer()->querySalesOrder();
        $orderItemQuery = $this->getQueryContainer()->querySalesOrderItem();

        return new OrdersTable($orderQuery, $orderItemQuery);
    }

    /**
     * @return \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    public function getOmsFacade()
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_OMS);
    }

}
