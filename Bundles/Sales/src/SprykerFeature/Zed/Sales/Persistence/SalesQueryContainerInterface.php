<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Persistence;

use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;

interface SalesQueryContainerInterface
{

    /**
     * @param int $idSalesOrder
     *
     * @return SpySalesOrderQuery
     */
    public function querySalesOrderById($idSalesOrder);

    /**
     * @return SpySalesOrderQuery
     */
    public function querySalesOrder();

    /**
     * @var int $idOrder
     *
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder($idOrder);

    /**
     * @var int $idOrder
     *
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsWithState($idOrder);

    /**
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItem();

}
