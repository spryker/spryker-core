<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\Ratepay\Business\RatepayFacade getFacade()
 * @method \Spryker\Zed\Ratepay\Communication\RatepayCommunicationFactory getFactory()
 */
abstract class BaseCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(SpySalesOrder $orderEntity)
    {
        return $this
            ->getFactory()
            ->getSalesAggregator()
            ->getOrderTotalsByIdSalesOrder($orderEntity->getIdSalesOrder());
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getOrderItemTotalsByIdSalesOrderItem($idSalesOrderItem)
    {
        return $this
            ->getFactory()
            ->getSalesAggregator()
            ->getOrderItemTotalsByIdSalesOrderItem($idSalesOrderItem);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getPartialOrderTransferByOrderItems($orderItems)
    {
        $partialOrderTransfer = $this->getFactory()->createOrderTransfer();
        $items = $this->getFactory()->createOrderTransferItems($orderItems);
        $partialOrderTransfer->setItems($items);

        return $this
            ->getFactory()
            ->getSalesAggregator()
            ->getOrderTotalByOrderTransfer($partialOrderTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getOrderItemsTransfer(array $orderItems)
    {
        $orderTransferItems = [];
        foreach ($orderItems as $orderItem) {
            $orderTransferItems[$orderItem->getIdSalesOrderItem()] = $this
                ->getOrderItemTotalsByIdSalesOrderItem($orderItem->getIdSalesOrderItem());
        }

        return $orderTransferItems;
    }

}
