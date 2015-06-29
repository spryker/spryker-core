<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderCommentQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
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
    public function querySales()
    {
        $query = SpySalesOrderQuery::create();

        return $query;
    }

    /**
     * @var int $idOrder
     *
     * @return SpySalesOrderItemQuery
     */
    public function queryOrderItems($idOrder)
    {
        $query = $this->getFactory()->createPropelSpySalesOrderItemQuery();
        return $query->filterByFkSalesOrder($idOrder);
    }


    /**
     * @var int $idOrder
     *
     * @return SpySalesOrderItemQuery
     */
    public function queryOrderItemsWithState($idOrder)
    {
        $query = $this->queryOrderItems($idOrder);
        $query->joinWith('State');


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
    public function queryCommentsByOrderId($orderId)
    {
        $query = SpySalesOrderCommentQuery::create();

        return $query;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return SpySalesOrderQuery
     */
    public function querySalesById($idSalesOrder)
    {
        $query = SpySalesOrderQuery::create();
        $query->filterByIdSalesOrder($idSalesOrder);

        return $query;
    }
}
