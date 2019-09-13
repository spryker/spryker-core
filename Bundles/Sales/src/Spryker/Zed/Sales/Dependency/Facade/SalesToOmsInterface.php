<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Dependency\Facade;

use Orm\Zed\Sales\Persistence\SpySalesOrder;

interface SalesToOmsInterface
{
    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    public function getInitialStateEntity();

    /**
     * @param string $processName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    public function getProcessEntity($processName);

    /**
     * @param int $idOrderItem
     *
     * @return string[]
     */
    public function getManualEvents($idOrderItem);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param string $flag
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    public function getItemsWithFlag(SpySalesOrder $order, $flag);

    /**
     * @param int $idSalesOrder
     *
     * @return string[][]
     */
    public function getManualEventsByIdSalesOrder($idSalesOrder);

    /**
     * @param int $idSalesOrder
     *
     * @return array
     */
    public function getDistinctManualEventsByIdSalesOrder($idSalesOrder);

    /**
     * @param int $idSalesOrder
     *
     * @return array
     */
    public function getGroupedDistinctManualEventsByIdSalesOrder(int $idSalesOrder): array;

    /**
     * @return array
     */
    public function getOrderItemMatrix();

    /**
     * @param int $idOrder
     *
     * @return bool
     */
    public function isOrderFlaggedExcludeFromCustomer($idOrder);
}
