<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface OrderHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getCustomerOrder(OrderTransfer $orderTransfer);

    /**
     * @param int $idSalesOrder
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderTransferFromPersistenceByIdSalesOrder($idSalesOrder);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderTransferFromPersistenceBySalesOrder(SpySalesOrder $orderEntity): OrderTransfer;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function hydrateOrderItemTransfer(SpySalesOrderItem $orderItemEntity);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function hydrateOrderItemsToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateBaseOrderTransfer(SpySalesOrder $orderEntity);
}
