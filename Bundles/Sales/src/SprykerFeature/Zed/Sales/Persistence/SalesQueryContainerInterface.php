<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;

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
     * @var int
     *
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder($idOrder);

    /**
     * @var int
     *
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsWithState($idOrder);

    /**
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItem();

    /**
     * @return SpySalesExpenseQuery
     */
    public function querySalesExpense();

    /**
     * @param int $orderId
     *
     * @return SpySalesExpenseQuery
     */
    public function querySalesExpensesByOrderId($orderId);

    /**
     * @param int $idSalesOrderAddress
     *
     * @return SpySalesOrderAddressQuery
     */
    public function querySalesOrderAddressById($idSalesOrderAddress);

    /**
     * @param $idCustomer
     * @param Criteria|null $criteria
     *
     * @return SpySalesOrderQuery
     */
    public function querySalesOrdersByCustomerId($idCustomer, Criteria $criteria=null);

    /**
     * @param int $idSalesOrder
     * @param int $idCustomer
     *
     * @return SpySalesOrderQuery
     */
    public function querySalesOrderDetails($idSalesOrder, $idCustomer);

}
