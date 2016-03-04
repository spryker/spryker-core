<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface SalesQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderById($idOrder);

    /**
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrder();

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder($idOrder);

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsWithState($idOrder);

    /**
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItem();

    /**
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    public function querySalesExpense();

    /**
     * @api
     *
     * @param int $orderId
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    public function querySalesExpensesByOrderId($orderId);

    /**
     * @api
     *
     * @param int $idOrderAddress
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery
     */
    public function querySalesOrderAddressById($idOrderAddress);

    /**
     * @api
     *
     * @param int $idCustomer
     * @param \Propel\Runtime\ActiveQuery\Criteria|null $criteria
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrdersByCustomerId($idCustomer, Criteria $criteria = null);

    /**
     * @api
     *
     * @param int $idOrder
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderDetails($idOrder, $idCustomer);

}
