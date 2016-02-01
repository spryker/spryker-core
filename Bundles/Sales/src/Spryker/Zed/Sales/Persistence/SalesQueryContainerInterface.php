<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Persistence;

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
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderById($idSalesOrder);

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrder();

    /**
     * @var int
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder($idOrder);

    /**
     * @var int
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsWithState($idOrder);

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItem();

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    public function querySalesExpense();

    /**
     * @param int $orderId
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    public function querySalesExpensesByOrderId($orderId);

    /**
     * @param int $idSalesOrderAddress
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery
     */
    public function querySalesOrderAddressById($idSalesOrderAddress);

    /**
     * @param $idCustomer
     * @param Criteria|null $criteria
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrdersByCustomerId($idCustomer, Criteria $criteria=null);

    /**
     * @param int $idSalesOrder
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderDetails($idSalesOrder, $idCustomer);

}
