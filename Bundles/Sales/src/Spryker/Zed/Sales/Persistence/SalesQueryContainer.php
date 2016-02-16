<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Sales\Persistence\SalesPersistenceFactory getFactory()
 */
class SalesQueryContainer extends AbstractQueryContainer implements SalesQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrder()
    {
        return $this->getFactory()->createSalesOrderQuery();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItem()
    {
        return $this->getFactory()->createSalesOrderItemQuery();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    public function querySalesExpense()
    {
        return $this->getFactory()->createSalesExpenseQuery();
    }

    /**
     * @var int
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder($idOrder)
    {
        $query = $this->getFactory()->createSalesOrderItemQuery();

        return $query->filterByFkSalesOrder($idOrder);
    }

    /**
     * @var int
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsWithState($idOrder)
    {
        $query = $this->querySalesOrderItemsByIdSalesOrder($idOrder);
        $query->joinWith('State');
        $query->joinWith('Process');

        return $query;
    }

    /**
     * @param int $idSalesOrderAddress
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery
     */
    public function querySalesOrderAddressById($idSalesOrderAddress)
    {
        $query = $this->getFactory()->createSalesOrderAddressQuery();
        $query->filterByIdSalesOrderAddress($idSalesOrderAddress);

        return $query;
    }

    /**
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    public function querySalesExpensesByOrderId($idOrder)
    {
        $query = $this->getFactory()->createSalesExpenseQuery();
        $query->filterByFkSalesOrder($idOrder);

        return $query;
    }

    /**
     * @param int $idOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function queryOrderItemById($idOrderItem)
    {
        $query = $this->getFactory()->createSalesOrderItemQuery();
        $query->filterByIdSalesOrderItem($idOrderItem);

        return $query;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderCommentQuery
     */
    public function queryComments()
    {
        $query = $this->getFactory()->createSalesOrderCommentQuery();

        return $query;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderById($idSalesOrder)
    {
        $query = $this->getFactory()->createSalesOrderQuery();
        $query->filterByIdSalesOrder($idSalesOrder);

        return $query;
    }

    /**
     * @param int $idCustomer
     * @param \Propel\Runtime\ActiveQuery\Criteria|null $criteria
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrdersByCustomerId($idCustomer, Criteria $criteria = null)
    {
        $query = $this->getFactory()->createSalesOrderQuery();
        $query->filterByFkCustomer($idCustomer);

        return $query;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderDetails($idSalesOrder)
    {
        $query = $this->getFactory()->createSalesOrderQuery()
         ->setModelAlias('order')
         ->filterByIdSalesOrder($idSalesOrder)
         ->innerJoinWith('order.BillingAddress billingAddress')
         ->innerJoinWith('billingAddress.Country billingCountry')
         ->innerJoinWith('order.ShippingAddress shippingAddress')
         ->innerJoinWith('shippingAddress.Country shippingCountry')
         ->innerJoinWithShipmentMethod();

        return $query;
    }

}
