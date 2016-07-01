<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Business;

use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Refund\Business\RefundBusinessFactory getFactory()
 */
class RefundFacade extends AbstractFacade implements RefundFacadeInterface
{

    /**
     * Specification:
     * - Calculates refund amount for given OrderTransfer and OrderItems which should be refunded.
     * - Adds refundable amount to RefundTransfer.
     * - Adds items with canceled amount to RefundTransfer.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefund(array $salesOrderItems, SpySalesOrder $salesOrderEntity)
    {
        return $this->getFactory()->createRefundCalculator()->calculateRefund($salesOrderItems, $salesOrderEntity);
    }

    /**
     * Specification:
     * - Persists calculated Refund amount.
     * - Sets calculated refund amount as canceled amount to each sales order item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function saveRefund(RefundTransfer $refundTransfer)
    {
        return $this->getFactory()->createRefundSaver()->saveRefund($refundTransfer);
    }

}
