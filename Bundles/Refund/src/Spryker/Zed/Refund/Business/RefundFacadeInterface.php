<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

interface RefundFacadeInterface
{
    /**
     * Specification:
     * - Calculates refund amount for given OrderTransfer and OrderItems which should be refunded.
     * - Adds refundable amount to RefundTransfer.
     * - Adds items with canceled amount to RefundTransfer.
     * - Uses calculator plugin stack for calculation.
     *
     * @api
     *
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefund(array $salesOrderItems, SpySalesOrder $salesOrderEntity);

    /**
     * Specification:
     * - Calculates refundable item amount.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefundableItemAmount(RefundTransfer $refundTransfer, OrderTransfer $orderTransfer, array $salesOrderItems);

    /**
     * Specification:
     * - Calculates refundable expense amount.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefundableExpenseAmount(RefundTransfer $refundTransfer, OrderTransfer $orderTransfer, array $salesOrderItems);

    /**
     * Specification:
     * - Persists calculated Refund amount.
     * - Sets calculated refund amount as canceled amount to each sales order item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return bool
     */
    public function saveRefund(RefundTransfer $refundTransfer);
}
