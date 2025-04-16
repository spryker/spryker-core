<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\OrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface OrderReaderInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return array<string>
     */
    public function getDistinctOrderStates(int $idSalesOrder): array;

    /**
     * @param \Generated\Shared\Transfer\OrderCriteriaTransfer $orderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByOrderCriteria(OrderCriteriaTransfer $orderCriteriaTransfer): ?OrderTransfer;

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrder(int $idSalesOrder): ?OrderTransfer;

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrderItem(int $idSalesOrderItem): ?OrderTransfer;
}
