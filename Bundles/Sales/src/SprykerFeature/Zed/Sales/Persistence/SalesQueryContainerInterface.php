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
    public function querySalesById($idSalesOrder);

    /**
     * @return SpySalesOrderQuery
     */
    public function querySales();

    /**
     * @var int $idOrder
     *
     * @return SpySalesOrderItemQuery
     */
    public function queryOrderItems($idOrder);

    /**
     * @var int $idOrder
     *
     * @return SpySalesOrderItemQuery
     */
    public function queryOrderItemsWithState($idOrder);

}
