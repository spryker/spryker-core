<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTotalsTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Sales\Persistence\SalesPersistenceFactory getFactory()
 */
class SalesRepository extends AbstractRepository implements SalesRepositoryInterface
{
    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrderListByCustomerReference(string $customerReference): OrderListTransfer
    {
        $orderEntity = $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByCustomerReference($customerReference)
            ->useOrderTotalQuery()
            ->withColumn(SpySalesOrderTotalsTableMap::COL_GRAND_TOTAL, TotalsTransfer::GRAND_TOTAL)
            ->withColumn(SpySalesOrderTotalsTableMap::COL_REFUND_TOTAL, TotalsTransfer::REFUND_TOTAL)
            ->withColumn(SpySalesOrderTotalsTableMap::COL_TAX_TOTAL, TotalsTransfer::TAX_TOTAL)
            ->withColumn(SpySalesOrderTotalsTableMap::COL_CANCELED_TOTAL, TotalsTransfer::CANCELED_TOTAL)
            ->withColumn(SpySalesOrderTotalsTableMap::COL_SUBTOTAL, TotalsTransfer::SUBTOTAL)
            ->endUse()
            ->select([
                SpySalesOrderTableMap::COL_CREATED_AT,
                SpySalesOrderTableMap::COL_ORDER_REFERENCE,
            ])
            ->find();

        return $this->getFactory()
            ->createSalesMapper()
            ->mapSalesOrderListTransfer($orderEntity);
    }

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function findOrderByOrderReference(string $orderReference): OrderTransfer
    {
        $orderData = $this->getFactory()
            ->createSalesOrderQuery()
            ->filterByOrderReference($orderReference)
            ->useOrderTotalQuery()
            ->withColumn(SpySalesOrderTotalsTableMap::COL_GRAND_TOTAL, TotalsTransfer::GRAND_TOTAL)
            ->withColumn(SpySalesOrderTotalsTableMap::COL_REFUND_TOTAL, TotalsTransfer::REFUND_TOTAL)
            ->withColumn(SpySalesOrderTotalsTableMap::COL_TAX_TOTAL, TotalsTransfer::TAX_TOTAL)
            ->withColumn(SpySalesOrderTotalsTableMap::COL_CANCELED_TOTAL, TotalsTransfer::CANCELED_TOTAL)
            ->withColumn(SpySalesOrderTotalsTableMap::COL_SUBTOTAL, TotalsTransfer::SUBTOTAL)
            ->endUse()
            ->select([
                SpySalesOrderTableMap::COL_CREATED_AT,
                SpySalesOrderTableMap::COL_ORDER_REFERENCE,
            ])
            ->findOne();

        return $this->getFactory()
            ->createSalesMapper()
            ->mapSalesOrderTransfer($orderData);
    }
}
