<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Propel\Runtime\Collection\ArrayCollection;

class SalesMapper implements SalesMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ArrayCollection $ordersTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function mapSalesOrderListTransfer(ArrayCollection $ordersTransfer): OrderListTransfer
    {
        $orderListTransfer = new OrderListTransfer();

        foreach ($ordersTransfer->getData() as $order) {
            $orderListTransfer->addOrder((new OrderTransfer())
                ->setCreatedAt($order[SpySalesOrderTableMap::COL_CREATED_AT])
                ->setOrderReference($order[SpySalesOrderTableMap::COL_ORDER_REFERENCE])
                ->setTotals((new TotalsTransfer())
                    ->setGrandTotal($order[TotalsTransfer::GRAND_TOTAL])
                    ->setSubtotal($order[TotalsTransfer::SUBTOTAL])
                    ->setRefundTotal($order[TotalsTransfer::REFUND_TOTAL])
                    ->setCanceledTotal($order[TotalsTransfer::CANCELED_TOTAL])));
        }

        return $orderListTransfer;
    }

    /**
     * @param array $orderData
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapSalesOrderTransfer(array $orderData): OrderTransfer
    {
        $orderTransfer = new OrderTransfer();

        $orderTransfer->setCreatedAt($orderData[SpySalesOrderTableMap::COL_CREATED_AT])
            ->setOrderReference($orderData[SpySalesOrderTableMap::COL_ORDER_REFERENCE])
            ->setTotals((new TotalsTransfer())
                ->setGrandTotal($orderData[TotalsTransfer::GRAND_TOTAL])
                ->setSubtotal($orderData[TotalsTransfer::SUBTOTAL])
                ->setRefundTotal($orderData[TotalsTransfer::REFUND_TOTAL])
                ->setCanceledTotal($orderData[TotalsTransfer::CANCELED_TOTAL]));

        return $orderTransfer;
    }
}
