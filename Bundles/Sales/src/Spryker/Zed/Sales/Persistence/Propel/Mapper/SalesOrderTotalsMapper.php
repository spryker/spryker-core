<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;

class SalesOrderTotalsMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderTotals
     */
    public function mapSalesOrderTotalsEntity(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SpySalesOrderTotals {
        $taxTotal = 0;
        if ($quoteTransfer->getTotals()->getTaxTotal()) {
            $taxTotal = $quoteTransfer->getTotals()->getTaxTotal()->getAmount();
        }

        $salesOrderTotalsEntity = new SpySalesOrderTotals();
        $salesOrderTotalsEntity->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $salesOrderTotalsEntity->fromArray($quoteTransfer->getTotals()->toArray());
        $salesOrderTotalsEntity->setTaxTotal($taxTotal);
        $salesOrderTotalsEntity->setOrderExpenseTotal($quoteTransfer->getTotals()->getExpenseTotal());

        return $salesOrderTotalsEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderTotals $salesOrderTotalsEntity
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Generated\Shared\Transfer\TaxTotalTransfer $taxTotalTransfer
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    public function mapSalesOrderTotalsTransfer(
        SpySalesOrderTotals $salesOrderTotalsEntity,
        TotalsTransfer $totalsTransfer,
        TaxTotalTransfer $taxTotalTransfer
    ): TotalsTransfer {
        $taxTotalTransfer->setAmount($salesOrderTotalsEntity->getTaxTotal());
        $totalsTransfer->setTaxTotal($taxTotalTransfer);

        $totalsTransfer->setExpenseTotal($salesOrderTotalsEntity->getOrderExpenseTotal());
        $totalsTransfer->setRefundTotal($salesOrderTotalsEntity->getRefundTotal());
        $totalsTransfer->setGrandTotal($salesOrderTotalsEntity->getGrandTotal());
        $totalsTransfer->setSubtotal($salesOrderTotalsEntity->getSubtotal());
        $totalsTransfer->setDiscountTotal($salesOrderTotalsEntity->getDiscountTotal());
        $totalsTransfer->setCanceledTotal($salesOrderTotalsEntity->getCanceledTotal());

        return $totalsTransfer;
    }
}
