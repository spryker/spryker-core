<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesPersistence;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderCommentQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;

/**
 * @method SalesPersistence getFactory()
 */
class SalesQueryContainer extends AbstractQueryContainer implements SalesQueryContainerInterface
{

    /**
     * @return SpySalesOrderQuery
     */
    public function querySalesOrder()
    {
        return new SpySalesOrderQuery();
    }

    /**
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItem()
    {
        return new SpySalesOrderItemQuery();
    }

    /**
     * @return SpySalesExpenseQuery
     */
    public function querySalesExpense()
    {
        return new SpySalesExpenseQuery();
    }

    /**
     * @var int
     *
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder($idOrder)
    {
        $query = new SpySalesOrderItemQuery();

        return $query->filterByFkSalesOrder($idOrder);
    }

    /**
     * @var int
     *
     * @return SpySalesOrderItemQuery
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
     * @return SpySalesOrderAddressQuery
     */
    public function querySalesOrderAddressById($idSalesOrderAddress)
    {
        $query = new SpySalesOrderAddressQuery();
        $query->filterByIdSalesOrderAddress($idSalesOrderAddress);

        return $query;
    }

    /**
     * @param int $idOrder
     *
     * @return SpySalesExpenseQuery
     */
    public function querySalesExpensesByOrderId($idOrder)
    {
        $query = new SpySalesExpenseQuery();
        $query->filterByFkSalesOrder($idOrder);

        return $query;
    }

    /**
     * @param $idOrderItem
     *
     * @return SpySalesOrderItemQuery
     */
    public function queryOrderItemById($idOrderItem)
    {
        $query = SpySalesOrderItemQuery::create();
        $query->filterByIdSalesOrderItem($idOrderItem);

        return $query;
    }

    /**
     * @return SpySalesOrderCommentQuery
     */
    public function queryComments()
    {
        $query = SpySalesOrderCommentQuery::create();

        return $query;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return SpySalesOrderQuery
     */
    public function querySalesOrderById($idSalesOrder)
    {
        $query = SpySalesOrderQuery::create();
        $query->filterByIdSalesOrder($idSalesOrder);

        return $query;
    }

    /**
     * @param $idCustomer
     * @param Criteria|null $criteria
     *
     * @return SpySalesOrderQuery
     */
    public function querySalesOrdersByCustomerId($idCustomer, Criteria $criteria = null)
    {
        $query = SpySalesOrderQuery::create(null, $criteria);
        $query->filterByFkCustomer($idCustomer);

        return $query;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idCustomer
     *
     * @return SpySalesOrderQuery
     */
    public function querySalesOrderDetails($idSalesOrder, $idCustomer)
    {
        $query = SpySalesOrderQuery::create('order');
        $query
            ->filterByIdSalesOrder($idSalesOrder)
            ->filterByFkCustomer($idCustomer)
        ;

        $query
            ->innerJoinWith('order.BillingAddress billingAddress')
            ->innerJoinWith('billingAddress.Country billingCountry')
            ->innerJoinWith('order.ShippingAddress shippingAddress')
            ->innerJoinWith('shippingAddress.Country shippingCountry')
            ->innerJoinWithShipmentMethod()
        ;

        return $query;
    }

}
