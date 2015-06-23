<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderCommentQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;

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
     * @var int $orderId
     *
     * @return SpySalesOrderItem
     */
    public function queryOrderItems($orderId)
    {
        $query = SpySalesOrderItemQuery::create();
        $query->filterByFkSalesOrder($orderId);
        $query->withColumn('COUNT(*)', 'qty');
        $query->groupBySku();

        return $query;
    }

    /**
     * @param $orderItemId
     *
     * @return SpySalesOrderItemQuery
     */
    public function queryOrderItemById($orderItemId)
    {
        $query = SpySalesOrderItemQuery::create();
        $query->filterByIdSalesOrderItem($orderItemId);

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
