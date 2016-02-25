<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;

interface RefundFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function calculateRefundableAmount(OrderTransfer $orderTransfer);

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\RefundTransfer[]
     */
    public function getRefundsByIdSalesOrder($idSalesOrder);

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function saveRefund(RefundTransfer $refundTransfer);

    /**
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem[]
     */
    public function getRefundableItems($idOrder);

    /**
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense[]
     */
    public function getRefundableExpenses($idOrder);

}
