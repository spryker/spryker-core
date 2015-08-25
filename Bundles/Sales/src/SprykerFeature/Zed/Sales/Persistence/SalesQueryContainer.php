<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesPersistence;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesExpenseQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddressQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderCommentQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;

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
        return $this->getFactory()->createPropelSpySalesOrderQuery();
    }

    /**
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItem()
    {
        return $this->getFactory()->createPropelSpySalesOrderItemQuery();
    }

    /**
     * @var int
     *
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder($idOrder)
    {
        $query = $this->getFactory()->createPropelSpySalesOrderItemQuery();

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
        $query = $this->getFactory()->createPropelSpySalesOrderAddressQuery();
        $query->filterByIdSalesOrderAddress($idSalesOrderAddress);

        return $query;
    }

    /**
     * @param int $orderId
     *
     * @return SpySalesExpenseQuery
     */
    public function querySalesExpensesByOrderId($orderId)
    {
        $query = $this->getFactory()->createPropelSpySalesExpenseQuery();
        $query->filterByFkSalesOrder($orderId);

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
     * @param $orderId
     *
     * @return SpySalesOrderCommentQuery
     */
    public function queryComments($orderId)
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
    public function querySalesOrdersByCustomerId($idCustomer, Criteria $criteria=null)
    {
        $query = SpySalesOrderQuery::create(null, $criteria);
        $query->filterByFkCustomer($idCustomer);

        return $query;
    }

}
